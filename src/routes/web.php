<?php

use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Infobox\Http\Controllers\DynamicPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

$adminPath = config('elfcms.elfcms.admin_path') ?? 'admin';
$adminPath = trim($adminPath,'/');

Route::group(['middleware'=>['web', 'locales', 'cookie']],function() use ($adminPath) {

    Route::name('admin.')->middleware(['admin','access'])->group(function() use ($adminPath) {


        Route::prefix($adminPath . '/infobox')->name('infobox.')->group(function() use ($adminPath) {
            Route::get('/nav/{infobox?}/{category?}', [\Elfcms\Infobox\Http\Controllers\InfoboxNavigator::class, 'index'])->name('nav');
            Route::resource('/infoboxes', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxController::class)->names(['index' => 'infoboxes']);
            Route::resource('/items', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxItemController::class)->names(['index' => 'items']);
            Route::resource('/categories', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxCategoryController::class)->names(['index' => 'categories']);

            Route::post('/create-page/{infobox}',[\Elfcms\Infobox\Http\Controllers\DynamicPageController::class, 'createPage'])->name('create-page');

            Route::name('properties.')->group(function() use ($adminPath) {
                Route::resource('/{infobox}/properties/category', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxCategoryPropertyController::class);
                Route::resource('/{infobox}/properties/item', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxItemPropertyController::class);
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

        Route::name('ajax.')->group(function() {

            Route::name('infobox.')->group(function() {
                Route::post('/elfcms/api/infobox/{type}/lineorder', [Elfcms\Infobox\Http\Controllers\Ajax\InfoboxController::class, 'lineOrder']);

                Route::name('property.')->group(function() {
                    Route::name('item.')->group(function() {
                        Route::get('/elfcms/api/infobox/property/item/list/{byId?}', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'list'])->name('list');
                        Route::get('/elfcms/api/infobox/property/item/empty-property', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'emptyItem'])->name('empty-item');
                        Route::post('/elfcms/api/infobox/property/item/fullsave', [\Elfcms\Infobox\Http\Controllers\InfoboxItemPropertyController::class, 'save'])->name('fullsave');
                    });
                    Route::name('category.')->group(function() {
                        Route::get('/elfcms/api/infobox/property/category/list/{byId?}', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'list'])->name('list');
                        Route::get('/elfcms/api/infobox/property/category/empty-property', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'emptyItem'])->name('empty-item');
                        Route::post('/elfcms/api/infobox/property/category/fullsave', [\Elfcms\Infobox\Http\Controllers\InfoboxCategoryPropertyController::class, 'save'])->name('fullsave');
                    });
                });
            });

        });

    });
    

});
