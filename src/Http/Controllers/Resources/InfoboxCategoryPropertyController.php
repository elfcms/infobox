<?php

namespace Elfcms\Infobox\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategoryProperty;
use Illuminate\Http\Request;

class InfoboxCategoryPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Elfcms\Infobox\Models\Infobox  $infobox
     * @return \Illuminate\Http\Response
     */
    public function index(Infobox $infobox)
    {
        $dataTypes = DataType::all();
        $properties = InfoboxCategoryProperty::all();
        return view('elfcms::admin.infobox.properties.index',[
            'page' => [
                'title' => __('infobox::default.category_properties'),
                'current' => url()->current(),
            ],
            'properties' => $properties,
            'data_types' => $dataTypes,
            'infobox' => $infobox,
            'type' => 'category',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InfoboxCategoryProperty  $infoboxCategoryProperty
     * @return \Illuminate\Http\Response
     */
    public function show(InfoboxCategoryProperty $infoboxCategoryProperty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InfoboxCategoryProperty  $infoboxCategoryProperty
     * @return \Illuminate\Http\Response
     */
    public function edit(InfoboxCategoryProperty $infoboxCategoryProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InfoboxCategoryProperty  $infoboxCategoryProperty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfoboxCategoryProperty $infoboxCategoryProperty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InfoboxCategoryProperty  $infoboxCategoryProperty
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfoboxCategoryProperty $infoboxCategoryProperty)
    {
        //
    }
}
