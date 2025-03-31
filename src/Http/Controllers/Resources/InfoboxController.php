<?php

namespace Elfcms\Infobox\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Infobox\Models\Infobox;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfoboxController extends Controller
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
            $infoboxes = Infobox::where('title','like',"%{$search}%")->orderBy($order, $trend)->paginate(30);

        }
        else {
            $infoboxes = Infobox::orderBy($order, $trend)->paginate(30);

        }

        return view('elfcms::admin.infobox.infobox.index',[
            'page' => [
                'title' => __('infobox::default.infoboxes'),
                'current' => url()->current(),
            ],
            'infoboxes' => $infoboxes,
            'params' => $request->all(),
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.infobox.infobox.create',[
            'page' => [
                'title' => __('infobox::default.create_infobox'),
                'current' => url()->current(),
            ],
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
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Infobox\Models\Infobox,slug',
        ]);

        $validated['description'] = $request->description;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $infobox = Infobox::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.infobox.nav',['infobox'=>$infobox]))->with('success',__('infobox::default.infobox_created_successfully'));
        }

        return redirect(route('admin.infobox.infoboxes.edit',$infobox))->with('success',__('infobox::default.infobox_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Infobox  $infobox
     * @return \Illuminate\Http\Response
     */
    public function show(Infobox $infobox)
    {
        return view('elfcms::admin.infobox.infobox.show',[
            'page' => [
                'title' => __('infobox::default.infobox') . '"' . $infobox->title . '"',
                'current' => url()->current(),
            ],
            'infobox' => $infobox
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Infobox  $infobox
     * @return \Illuminate\Http\Response
     */
    public function edit(Infobox $infobox)
    {
        return view('elfcms::admin.infobox.infobox.edit',[
            'page' => [
                'title' => __('infobox::default.edit_infobox',['infobox'=>$infobox->title]),
                'current' => url()->current(),
            ],
            'infobox' => $infobox
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Infobox  $infobox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Infobox $infobox)
    {
        if ($request->notedit && $request->notedit == 1) {
            $infobox->active = empty($request->active) ? 0 : 1;

            $infobox->save();

            return redirect(route('admin.infobox.infoboxes'))->with('success',__('infobox::default.item_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            $validated = $request->validate([
                'title' => 'required',
            ]);
            if (Infobox::where('slug',$request->slug)->where('id','<>',$infobox->id)->first()) {
                return redirect(route('admin.infobox.infoboxes.edit',$infobox))->withErrors([
                    'slug' => __('infobox::default.item_already_exists')
                ]);
            }

            $infobox->title = $validated['title'];
            $infobox->slug = $request->slug;
            $infobox->description = $request->description;
            $infobox->meta_keywords = $request->meta_keywords;
            $infobox->meta_description = $request->meta_description;
            $infobox->active = empty($request->active) ? 0 : 1;

            $infobox->save();

            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.infobox.nav',['infobox'=>$infobox]))->with('success',__('infobox::default.item_edited_successfully'));
            }

            return redirect(route('admin.infobox.infoboxes.edit',$infobox))->with('success',__('infobox::default.item_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Infobox  $infobox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Infobox $infobox)
    {
        if (!$infobox->delete()) {
            return redirect()->back()->withErrors(['deleteerror'=>__('infobox::default.error_of_infobox_deleting')]);
        }

        return redirect()->back()->with('success',__('infobox::default.infobox_deleted_successfully'));
    }
}
