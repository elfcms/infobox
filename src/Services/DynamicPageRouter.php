<?php

namespace Elfcms\Infobox\Services;

use Elfcms\Elfcms\Models\Page;
use Elfcms\Infobox\Http\Controllers\DynamicPageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class DynamicPageRouter
{

    public static function moduleRoutes(Page $page)
    {
        try {
            if (Schema::hasTable('pages')) {
                $pages = Page::where('active', 1)
                    ->where('module', 'infobox')
                    ->get();

                foreach ($pages as $page) {
                    if ($page->path) {
                        // Main page route
                        Route::get($page->path, function () use ($page) {
                            return app(DynamicPageController::class)->show($page);
                        });

                        $options = $page->module_options ?? [];

                        $categoryPath = $options['category_path'] ?? '';
                        $categoryPath = trim($categoryPath, '/');
                        if ($categoryPath !== '') $categoryPath .= '/';
                        if (!empty($options['show_categories'])) {
                            Route::get($page->path . '/' . $categoryPath . '{category:slug}', function ($categorySlug) use ($page) {
                                $controller = app(DynamicPageController::class);
                                return $controller->showCategory($page, $categorySlug);
                            });
                        }

                        if (!empty($options['show_items'])) {
                            $itemPath = $options['item_path'] ?? '';
                            $itemPath = trim($itemPath, '/');
                            if ($itemPath !== '') $itemPath .= '/';
                            if (!empty($options['use_category_path'])) {
                                $itemPath = $categoryPath . '{category:slug}/' . $itemPath;
                            }
                            elseif (empty($itemPath)) {
                                $itemPath = 'items/';
                            }
                            Route::get($page->path . '/' . $itemPath . '{item:slug}', function ($itemSlug) use ($page) {
                                $controller = app(DynamicPageController::class);
                                return $controller->showItem($page, $itemSlug);
                            });
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            //
        }
    }
}
