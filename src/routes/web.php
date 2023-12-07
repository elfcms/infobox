<?php

use Elfcms\Elfcms\Models\DataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.elfcms.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::name('infobox.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/infobox/nav/{infobox?}/{category?}', [\Elfcms\Infobox\Http\Controllers\InfoboxNavigator::class, 'index'])->name('nav');
            Route::resource($adminPath . '/infobox/infoboxes', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxController::class)->names(['index' => 'infoboxes']);
            Route::resource($adminPath . '/infobox/items', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxItemController::class)->names(['index' => 'items']);
            Route::resource($adminPath . '/infobox/categories', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxCategoryController::class)->names(['index' => 'categories']);

            Route::name('properties.')->group(function() use ($adminPath) {
                Route::resource($adminPath . '/infobox/{infobox}/properties/category', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxCategoryPropertyController::class);
                Route::resource($adminPath . '/infobox/{infobox}/properties/item', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxItemPropertyController::class);
            });
        });
        Route::get($adminPath . '/ajax/json/infobox/datatypes',function(Request $request){
            $result = [];
            if ($request->ajax()) {
                $result = DataType::all()->toArray();
                $result = json_encode($result);
            }
            return $result;
        });

        Route::get($adminPath . '/infobox/ajax/property/item/list/{byId?}', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'list'])->name('infobox.ajax.property.item.list');
        Route::get($adminPath . '/infobox/ajax/property/item/empty-property', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'emptyItem'])->name('infobox.ajax.property.item.empty-item');
        Route::post($adminPath . '/infobox/ajax/property/item/fullsave', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'save'])->name('infobox.ajax.property.item.fullsave');

        Route::get($adminPath . '/infobox/ajax/property/category/list/{byId?}', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'list'])->name('infobox.ajax.property.category.list');
        Route::get($adminPath . '/infobox/ajax/property/category/empty-property', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'emptyItem'])->name('infobox.ajax.property.category.empty-item');
        Route::post($adminPath . '/infobox/ajax/property/category/fullsave', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'save'])->name('infobox.ajax.property.category.fullsave');

    });

});
