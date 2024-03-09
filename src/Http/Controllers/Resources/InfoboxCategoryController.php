<?php

namespace Elfcms\Infobox\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FileCatalog;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Elfcms\Infobox\Models\InfoboxCategoryProperty;
use Elfcms\Infobox\Models\InfoboxCategoryPropertyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfoboxCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }
        $search = $request->search ?? '';
        //$categories = InfoboxCategory::where('parent_id',null)->get();
        if (!empty($search)) {
            $categories = InfoboxCategory::where('title','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $categories = InfoboxCategory::flat(trend: $trend, order: $order, count: $count, search: $search);
        }

        return view('elfcms::admin.infobox.categories.index',[
            'page' => [
                'title' => __('infobox::default.categories'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
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

        return view('elfcms::admin.infobox.categories.create',[
            'page' => [
                'title' => __('infobox::default.create_category'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'infoboxes' => $infoboxes,
            'currentInfobox' => $currentInfobox,
            'firstInfobox' => $firstInfobox,
            'category_id' => $category_id,
            'curentCategory' => $curentCategory,
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
            'slug' => 'required|unique:Elfcms\Infobox\Models\InfoboxCategory,slug',
            //'image' => 'nullable|file|max:512',
            //'preview' => 'nullable|file|max:256'
        ]);


        //$validated['image'] = $image_path;
        //$validated['preview'] = $preview_path;
        $validated['infobox_id'] = $request->infobox_id;
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        //$validated['public_time'] = $public_time;
        //$validated['end_time'] = $end_time;
        $validated['parent_id'] = $request->parent_id;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;

        $category = InfoboxCategory::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.infobox.nav',['infobox'=>$category->infobox,'category'=>$category->parent]))->with('success',__('infobox::default.category_created_successfully'));
        }

        return redirect(route('admin.infobox.categories.edit',$category))->with('categoryresult',__('elfcms::default.category_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InfoboxCategory $category)
    {
        /* if (!empty($category->end_time)) {
            $category->end_time_time = date('H:i',strtotime($category->end_time));
            $category->end_time = date('Y-m-d',strtotime($category->end_time));
        }
        if (!empty($category->public_time)) {
            $category->public_time_time = date('H:i',strtotime($category->public_time));
            $category->public_time = date('Y-m-d',strtotime($category->public_time));
        } */
        $category->created = '';
        $category->updated = '';
        if (!empty($category->created_at)) {
            $category->created = date('d.m.Y H:i:s',strtotime($category->created_at));
        }
        if (!empty($category->updated_at)) {
            $category->updated = date('d.m.Y H:i:s',strtotime($category->updated_at));
        }
        $exclude =InfoboxCategory::childrenid($category->id,true);
        $categories = InfoboxCategory::where('infobox_id',$category->infobox->id)->whereNotIn('id',$exclude)->get();
        $properties = InfoboxCategoryProperty::where('infobox_id',$category->infobox->id)->get();
        foreach ($properties as $property) {
            //if ($parameter->id == 9) dd($parameter->product_values($shopProduct->id));
            $property->value = $property->values($category->id);
            /* if ($property->data_type->code == 'color') {
                //dd($property->value);
                $property->colorData = ShopColor::find($property->value);
            } */
            //if ($parameter->id == 9) dd($parameter->value);
        }
        return view('elfcms::admin.infobox.categories.edit',[
            'page' => [
                'title' => __('infobox::default.edit_category',['category'=>$category->title]),
                'current' => url()->current(),
            ],
            'category' => $category,
            'categories' => $categories,
            'properties' => $properties
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfoboxCategory $category)
    {
        if ($request->notedit && $request->notedit == 1) {
            $category->active = empty($request->active) ? 0 : 1;

            $category->save();

            return redirect(route('admin.infobox.categories'))->with('categoryresult',__('elfcms::default.category_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            $validated = $request->validate([
                'infobox_id' => 'required',
                'title' => 'required',
                //'slug' => 'required|unique:Elfcms\Infobox\Models\InfoboxCategory,slug',
                'slug' => 'required',
                //'image' => 'nullable|file|max:512',
                //'preview' => 'nullable|file|max:256'
            ]);
            if (InfoboxCategory::where('slug',$request->slug)->where('id','<>',$category->id)->first()) {
                return redirect(route('admin.infobox.categories.edit'))->withErrors([
                    'slug' => 'Category already exists'
                ]);
            }

            $category->infobox_id = $validated['infobox_id'];
            $category->title = $validated['title'];
            $category->slug = $validated['slug'];
            $category->parent_id = $request->parent_id;
            //$category->image = $image_path;
            //$category->preview = $preview_path;
            $category->description = $request->description;
            $category->active = empty($request->active) ? 0 : 1;
            //$category->public_time = $public_time;
            //$category->end_time = $end_time;
            $category->meta_keywords = $request->meta_keywords;
            $category->meta_description = $request->meta_description;

            $category->save();


            /* Properties */
            if (!empty($request->property)) {
                $properties = $category->infobox->categoryProperties;
                foreach($properties as $property) {
                    if (!isset($request->property[$property->id])) {
                        if ($property->data_type->code == 'bool') {
                            $propertyValue = InfoboxCategoryPropertyValue::updateOrCreate(
                                ['category_id' => $category->id, 'property_id' => $property->id],
                                [$property->data_type->code . '_value' => 0]
                            );
                        }
                        elseif ($property->data_type->code == 'list') {
                            $propertyValue = InfoboxCategoryPropertyValue::updateOrCreate(
                                ['category_id' => $category->id, 'property_id' => $property->id],
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

                        $propertyValue = InfoboxCategoryPropertyValue::updateOrCreate(
                            ['category_id' => $category->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => $paramValue]
                        );
                    }
                    if (!empty($request->file()['property']) && !empty($request->file()['property'][$property->id])) {
                        $paramValue = $request->file()['property'][$property->id];
                        if ($property->data_type->code != 'file' && $property->data_type->code != 'image') {
                            continue;
                        }
                        $originalName = $paramValue[$property->data_type->code]->getClientOriginalName();
                        $file_path = $request->property[$property->id]['path'];
                        $file_path = $paramValue[$property->data_type->code]->store('elfcms/infobox/properties/category/' . $property->data_type->code . 's');
                        FileCatalog::set($file_path,$originalName);
                        $propertyValue = InfoboxCategoryPropertyValue::updateOrCreate(
                            ['category_id' => $category->id, 'property_id' => $property->id],
                            [$property->data_type->code . '_value' => $file_path]
                        );
                    }
                }
            }
            /* /Properties */

            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.infobox.nav',['infobox'=>$category->infobox,'category'=>$category->parent]))->with('success',__('infobox::default.category_edited_successfully'));
            }

            return redirect(route('admin.infobox.categories.edit',$category))->with('categoryresult',__('infobox::default.category_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfoboxCategory $category)
    {
        if (!$category->delete()) {
            return redirect()->back()->withErrors(['categoryerror'=>__('infobox::default.error_of_category_deleting')]);
        }

        return redirect()->back()->with('categoryresult',__('infobox::default.category_deleted_successfully'));
    }
}
