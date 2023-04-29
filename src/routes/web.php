<?php

use Elfcms\Basic\Models\DataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = Config::get('elfcms.basic.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie','start']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::name('infobox.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/infobox', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxController::class);
            Route::resource($adminPath . '/infobox/items', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxItemController::class)->names(['index' => 'items']);
            Route::resource($adminPath . '/infobox/categories', \Elfcms\Infobox\Http\Controllers\Resources\InfoboxCategoryController::class)->names(['index' => 'categories']);
        });
        Route::get($adminPath . '/ajax/json/infobox/datatypes',function(Request $request){
            $result = [];
            if ($request->ajax()) {
                $result = DataType::all()->toArray();
                $result = json_encode($result);
            }
            return $result;
        });

    });

});
