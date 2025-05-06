<?php

namespace Elfcms\Infobox\Services;

use Elfcms\Elfcms\Models\Page;
use Elfcms\Infobox\Http\Controllers\DynamicPageController;
use Elfcms\Infobox\Models\InfoboxCategory;
use Elfcms\Infobox\Models\InfoboxItem;
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
                
                $controller = new DynamicPageController;

                foreach ($pages as $page) {
                    if ($page->path) {
                        // Main page route
                        Route::get($page->path, function () use ($page, $controller) {
                            return $controller->show($page);
                        });

                        $options = $page->module_options ?? [];

                        // Category page route
                        $categoryPath = $options['category_path'] ?? '';
                        $categoryPath = trim($categoryPath, '/');
                        if ($categoryPath !== '') $categoryPath .= '/';
                        if (!empty($options['show_categories'])) {
                            Route::get($page->path . '/' . $categoryPath . '{category:slug}', function (InfoboxCategory $category) use ($page, $controller) {
                                return $controller->showCategory($page, $category);
                            });
                        }

                        // Item page route
                        if (!empty($options['show_items'])) {
                            $itemPath = $options['item_path'] ?? '';
                            $itemPath = trim($itemPath, '/');
                            if ($itemPath !== '') $itemPath .= '/';
                            if (!empty($options['use_category_path'])) {
                                $itemPath = $categoryPath . '{category:slug}/' . $itemPath;
                                Route::get($page->path . '/' . $itemPath . '{item:slug}', function (InfoboxCategory $category, InfoboxItem $item) use ($page, $controller) {
                                    return $controller->showCategoryItem($page, $category, $item);
                                });
                            } elseif (empty($itemPath)) {
                                $itemPath = 'items/';
                                Route::get($page->path . '/' . $itemPath . '{item:slug}', function (InfoboxItem $item) use ($page, $controller) {
                                    return $controller->showItem($page, $item);
                                });
                            }
                            else {
                                Route::get($page->path . '/' . $itemPath . '{item:slug}', function (InfoboxItem $item) use ($page, $controller) {
                                    return $controller->showItem($page, $item);
                                });
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            //
        }
    }
}
