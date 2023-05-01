<?php

namespace Elfcms\Infobox\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
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

        return view('infobox::admin.infobox.categories.index',[
            'page' => [
                'title' => 'Infobox categories',
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
        $categories = InfoboxCategory::all();
        $infoboxes = Infobox::active()->get();
        $currentInfobox = Infobox::where('id',$request->infobox)->orWhere('slug',$request->infobox)->first();
        $firstInfobox = Infobox::active()->first();
        return view('infobox::admin.infobox.categories.create',[
            'page' => [
                'title' => 'Create category',
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'infoboxes' => $infoboxes,
            'currentInfobox' => $currentInfobox,
            'firstInfobox' => $firstInfobox,
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

        /* $image_path = '';
        $preview_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/infobox/categories/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        if (!empty($request->file()['preview'])) {
            $preview = $request->file()['preview']->store('public/infobox/categories/preview');
            $preview_path = str_ireplace('public/','/storage/',$preview);
        } */

        /* $public_time = $request->public_time[0];

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
        } */

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

        return redirect(route('admin.infobox.categories.edit',$category))->with('categoryresult',__('shop::elf.category_created_successfully'));
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
        return view('infobox::admin.infobox.categories.edit',[
            'page' => [
                'title' => 'Edit category #' . $category->id,
                'current' => url()->current(),
            ],
            'category' => $category,
            'categories' => $categories,
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

            return redirect(route('admin.infobox.categories'))->with('categoryresult',__('shop::elf.category_edited_successfully'));
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
            /* $image_path = $request->image_path;
            $preview_path = $request->preview_path;
            if (!empty($request->file()['image'])) {
                $image = $request->file()['image']->store('public/infobox/categories/image');
                $image_path = str_ireplace('public/','/storage/',$image);
            }
            if (!empty($request->file()['preview'])) {
                $preview = $request->file()['preview']->store('public/infobox/categories/preview');
                $preview_path = str_ireplace('public/','/storage/',$preview);
            } */

            /* $public_time = $request->public_time[0];

            if (empty($request->public_time[1]) && !empty($public_time)) {
                $public_time .= ' 00:00:00';
            }
            elseif (!empty($public_time)) {
                $public_time .= ' '.$request->public_time[1];
            } */

            /* $end_time = $request->end_time[0];

            if (empty($request->end_time[1]) && !empty($end_time)) {
                $end_time .= ' 00:00:00';
            }
            elseif (!empty($end_time)) {
                $end_time .= ' '.$request->end_time[1];
            } */

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

            return redirect(route('admin.infobox.categories.edit',$category))->with('categoryresult',__('infobox::elf.category_edited_successfully'));
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
            return redirect(route('admin.infobox.categories'))->withErrors(['categoryerror'=>__('infobox::elf.error_of_category_deleting')]);
        }

        return redirect(route('admin.infobox.categories'))->with('categoryresult',__('infobox::elf.category_deleted_successfully'));
    }
}
