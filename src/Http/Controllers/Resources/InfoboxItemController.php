<?php

namespace Elfcms\Infobox\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\FileCatalog;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Elfcms\Infobox\Models\InfoboxItem;
use Elfcms\Infobox\Models\InfoboxItemOption;
use Elfcms\Infobox\Models\InfoboxItemProperty;
use Elfcms\Infobox\Models\InfoboxItemPropertyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfoboxItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $search = $request->search ?? '';
        if (!empty($search)) {
            $items = InfoboxItem::where('title','like',"%{$search}%")->orderBy($order, $trend)->paginate(30);

        }
        else {
            $items = InfoboxItem::orderBy($order, $trend)->paginate(30);

        }

        return view('elfcms::admin.infobox.items.index',[
            'page' => [
                'title' => __('infobox::default.infobox') . ' ' . __('infobox::default.items'),
                'current' => url()->current(),
            ],
            'items' => $items,
            'params' => $request->all(),
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category_id = null;
        $curentCategory = null;
        $categories = InfoboxCategory::all();
        $infoboxes = Infobox::active()->get();
        $currentInfobox = Infobox::where('id',$request->infobox)->orWhere('slug',$request->infobox)->first();
        $firstInfobox = Infobox::active()->first();
        if (!empty($request->category_id)) {
            $curentCategory = InfoboxCategory::find($request->category_id);
            if ($curentCategory) {
                $category_id = $request->category_id;
                if (empty($request->infobox)) {
                    $currentInfobox = $curentCategory->infobox;
                }
            }
        }
        $properties = new InfoboxItemProperty();
        if ($currentInfobox && !empty($currentInfobox->id)) {
            $properties = InfoboxItemProperty::where('infobox_id',$currentInfobox->id)->get();
            foreach ($properties as $property) {
                $property->value = $property->values($currentInfobox->id);
            }
        }
        return view('elfcms::admin.infobox.items.create',[
            'page' => [
                'title' => __('infobox::default.create_item'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'infoboxes' => $infoboxes,
            'currentInfobox' => $currentInfobox,
            'firstInfobox' => $firstInfobox,
            'category_id' => $category_id,
            'curentCategory' => $curentCategory,
            'properties' => $properties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'infobox_id' => 'required',
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Infobox\Models\InfoboxItem,slug'
        ]);


        $public_time = $request->public_time[0];

        if (empty($request->public_time[1]) && !empty($public_time)) {
            $public_time .= ' 00:00:00';
        }
        elseif (!empty($public_time)) {
            $public_time .= ' '.$request->public_time[1];
        }

        $end_time = $request->end_time[0];

        if (empty($request->end_time[1]) && !empty($end_time)) {
            $end_time .= ' 00:00:00';
        }
        elseif (!empty($end_time)) {
            $end_time .= ' '.$request->end_time[1];
        }

        $validated['category_id'] = $request->category_id;
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['public_time'] = $public_time;
        $validated['end_time'] = $end_time;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;

        $item = InfoboxItem::create($validated);

        if ($item && !empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || empty($param['type']) || empty($param['name'])) {
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $typeCodes = ['int','float','date','datetime'];
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code,$typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                $optionData = [
                    'name' => $param['name'],
                    'data_type_id' => $param['type'],
                    'value'.$type => $param['value'],
                ];
                $item->options()->create($optionData);
            }
        }


        if ($item) {

            /* Properties */
            if (!empty($request->property)) {
                $properties = $item->infobox->itemProperties;
                foreach($properties as $property) {
                    if (empty($request->property[$property->id])) {
                        if ($property->data_type->code == 'bool') {
                            $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                                ['item_id' => $item->id, 'property_id' => $property->id],
                                [$property->data_type->code . '_value' => 0]
                            );
                        }
                        elseif ($property->data_type->code == 'list' || $property->data_type->code == 'file') {
                            $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                                ['item_id' => $item->id, 'property_id' => $property->id],
                                [$property->data_type->code . '_value' => null]
                            );
                        }
                        continue;
                    }

                    if (!empty($request->property[$property->id])) {

                        $paramValue = $request->property[$property->id];
                        if (is_array($paramValue)) {
                            if (!empty($request->file()['property'][$property->id]['file'])) {
                                continue;
                            }
                            if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                                continue;
                            }
                            $paramValue = $paramValue['path'];
                        }
                        if ($property->data_type->code == 'list') {
                            if (empty($paramValue)) {
                                $paramValue = [];
                            }
                            if (!is_array($paramValue)) {
                                $paramValue = [$paramValue];
                            }
                            $paramValue = json_encode($paramValue);
                        }
                        /* if ($property->data_type->code == 'color') {
                            if ($paramValue == 0) {
                                $paramValue = null;
                            }
                        } */
                        if ($property->data_type->code == 'bool') {
                            $paramValue = 1;
                        }

                        $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                            ['item_id' => $item->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => $paramValue]
                        );
                    }

                    if (!empty($request->file()['property']) && !empty($request->file()['property'][$property->id])) {
                        $paramValue = $request->file()['property'][$property->id];
                        if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                            continue;
                        }
                        if (is_array($paramValue)) {
                            $paramValue = $paramValue[$property->data_type->code];
                        }
                        $originalName = $paramValue->getClientOriginalName();
                        $file_path = null;
                        if (is_array($request->property[$property->id])) $file_path = $request->property[$property->id]['path'];
                        $file = $paramValue->store('public/infobox/properties/item/' . $property->data_type->code . 's');
                        $file_path = str_ireplace('public/','/storage/',$file);
                        FileCatalog::set($file_path,$originalName);
                        $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                            ['item_id' => $item->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => $file_path]
                        );
                    }
                }
            }
            /* /Properties */
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.infobox.nav',['infobox'=>$item->infobox,'category'=>$item->category]))->with('success',__('elfcms::default.item_created_successfully'));
        }

        return redirect(route('admin.infobox.items.edit',$item))->with('itemresult',__('infobox::default.item_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InfoboxItem  $item
     * @return \Illuminate\Http\Response
     */
    public function show(InfoboxItem $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InfoboxItem  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(InfoboxItem $item)
    {
        if (!empty($item->end_time)) {
            $item->end_time_time = date('H:i',strtotime($item->end_time));
            $item->end_time = date('Y-m-d',strtotime($item->end_time));
        }
        if (!empty($item->public_time)) {
            $item->public_time_time = date('H:i',strtotime($item->public_time));
            $item->public_time = date('Y-m-d',strtotime($item->public_time));
        }
        $item->created = '';
        $item->updated = '';
        if (!empty($item->created_at)) {
            $item->created = date('d.m.Y H:i:s',strtotime($item->created_at));
        }
        if (!empty($item->updated_at)) {
            $item->updated = date('d.m.Y H:i:s',strtotime($item->updated_at));
        }
        $categories = InfoboxCategory::where('infobox_id',$item->infobox->id)->get();
        $properties = InfoboxItemProperty::where('infobox_id',$item->infobox->id)->get();
        foreach ($properties as $property) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopProduct->id));
            $property->value = $property->values($item->id);
            /* if ($property->data_type->code == 'color') {
                //dd($property->value);
                $property->colorData = ShopColor::find($property->value);
            } */
            //if ($parameter->id == 9) dd($parameter->value);
        }
        return view('elfcms::admin.infobox.items.edit',[
            'page' => [
                'title' => __('infobox::default.edit_item', ['item'=>$item->title]),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'item' => $item,
            'properties' => $properties
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InfoboxItem  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfoboxItem $item)
    {
        //dd($item->infobox->itemProperties[0]->data_type);
        if ($request->notedit && $request->notedit == 1) {
            $item->active = empty($request->active) ? 0 : 1;

            $item->save();

            return redirect(route('admin.infobox.items'))->with('itemresult',__('infobox::default.item_edited_successfully'));
        }
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required',//|unique:Elfcms\Infobox\Models\InfoboxItem,code',
        ]);
        if (InfoboxItem::where('slug',$request->slug)->where('id','<>',$item->id)->first()) {
            return redirect(route('admin.infobox.item.edit',$item->id))->withErrors([
                'slug' => __('infobox::default.item_already_exists')
            ]);
        }

        $public_time = $request->public_time[0];

        if (empty($request->public_time[1]) && !empty($public_time)) {
            $public_time .= ' 00:00:00';
        }
        elseif (!empty($public_time)) {
            $public_time .= ' '.$request->public_time[1];
        }

        $end_time = $request->end_time[0];

        if (empty($request->end_time[1]) && !empty($end_time)) {
            $end_time .= ' 00:00:00';
        }
        elseif (!empty($end_time)) {
            $end_time .= ' '.$request->end_time[1];
        }

        $item->slug = $request->slug;
        $item->title = $request->title;
        $item->category_id = $request->category_id;
        $item->description = $request->description;
        $item->active = empty($request->active) ? 0 : 1;
        $item->public_time = $public_time;
        $item->end_time = $end_time;
        $item->meta_keywords = $request->meta_keywords;
        $item->meta_description = $request->meta_description;

        /* $typeCodes = ['int','float','date','datetime'];

        if (!empty($request->options_exist)) {
            foreach ($request->options_exist as $oid => $param) {
                if (!empty($param['deleted']) && $oid > 0) {
                    $item->options()->find($oid)->delete();
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code,$typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                /* $option = InfoboxItemOption::find($oid);
                if ($option) {
                    $option['value'.$type] = $param['value'];
                    $option->name = $param['name'];
                    $option->data_type_id = $param['type'];
                    $option->save();
                } *
            }
        } */

        /* if (!empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || (empty($param['value']) && empty($param['text']))) {
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code,$typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                $optionData = [
                    'value'.$type => $param['value'],
                    'name' => $param['name'],
                    'data_type_id' => $param['type'],
                ];
                $item->options()->create($optionData);
            }
        } */

        $item->save();

        /* Properties */
        if (!empty($request->property)) {
            $properties = $item->infobox->itemProperties;
            foreach($properties as $property) {
                if (empty($request->property[$property->id])) {
                    if ($property->data_type->code == 'bool') {
                        $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                            ['item_id' => $item->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => 0]
                        );
                    }
                    elseif ($property->data_type->code == 'list' || $property->data_type->code == 'file') {
                        $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                            ['item_id' => $item->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => null]
                        );
                    }
                    continue;
                }

                if (!empty($request->property[$property->id])) {

                    $paramValue = $request->property[$property->id];
                    if (is_array($paramValue)) {
                        if (!empty($request->file()['property'][$property->id]['file'])) {
                            continue;
                        }
                        if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                            continue;
                        }
                        $paramValue = $paramValue['path'];
                    }
                    if ($property->data_type->code == 'list') {
                        if (empty($paramValue)) {
                            $paramValue = [];
                        }
                        if (!is_array($paramValue)) {
                            $paramValue = [$paramValue];
                        }
                        $paramValue = json_encode($paramValue);
                    }
                    /* if ($property->data_type->code == 'color') {
                        if ($paramValue == 0) {
                            $paramValue = null;
                        }
                    } */
                    if ($property->data_type->code == 'bool') {
                        $paramValue = 1;
                    }

                    $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                        ['item_id' => $item->id, 'property_id' => $property->id],
                        [$property->data_type->code . '_value' => $paramValue]
                    );
                }

                if (!empty($request->file()['property']) && !empty($request->file()['property'][$property->id])) {
                    $paramValue = $request->file()['property'][$property->id];
                    if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                        continue;
                    }
                    if (is_array($paramValue)) {
                        $paramValue = $paramValue[$property->data_type->code];
                    }
                    $originalName = $paramValue->getClientOriginalName();
                    $file_path = null;
                    if (is_array($request->property[$property->id])) $file_path = $request->property[$property->id]['path'];
                    $file = $paramValue->store('public/infobox/properties/item/' . $property->data_type->code . 's');
                    $file_path = str_ireplace('public/','/storage/',$file);
                    FileCatalog::set($file_path,$originalName);
                    $propertyValue = InfoboxItemPropertyValue::updateOrCreate(
                        ['item_id' => $item->id, 'property_id' => $property->id],
                        [$property->data_type->code . '_value' => $file_path]
                    );
                }
            }
        }
        /* /Properties */



        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.infobox.nav',['infobox'=>$item->infobox,'category'=>$item->category]))->with('success',__('elfcms::default.item_edited_successfully'));
        }

        return redirect(route('admin.infobox.items.edit',$item))->with('itemresult',__('infobox::default.item_edited_successfully'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InfoboxItem  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfoboxItem $item)
    {
        if (!$item->delete()) {
            return redirect()->back()->withErrors(['itemdelerror'=>__('infobox::default.error_of_item_deleting')]);
        }

        return redirect()->back()->with('itemresult',__('infobox::default.item_deleted_successfully'));
    }
}
