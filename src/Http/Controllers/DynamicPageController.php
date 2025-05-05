<?php

namespace Elfcms\Infobox\Http\Controllers;

use Elfcms\Elfcms\Facades\PageConfig;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Elfcms\Infobox\Models\InfoboxItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class DynamicPageController extends Controller
{
    public function show(Page $page)
    {
        // Getting page options
        $options = $page->module_options ?? [];

        $infoboxId = $page->module_id;
        if (!$infoboxId) {
            abort(404);
        }

        // Loading infobox
        $infobox = Infobox::findOrFail($infoboxId);

        // What to show
        $showCategories = $options['show_categories'] ?? false;
        $showItems = $options['show_items'] ?? false;
        $categoriesDepth = $options['categories_depth'] ?? 1;

        // Loading data
        $categories = [];
        if ($showCategories) {
            $categories = InfoboxCategory::where('infobox_id', $infobox->id)
                ->whereNull('parent_id') // Upper levels only
                ->with(['children' => function ($query) use ($categoriesDepth) {
                    $this->loadChildrenRecursive($query, $categoriesDepth - 1);
                }])
                ->get();
        }

        $items = [];
        if ($showItems) {
            $items = InfoboxItem::where('infobox_id', $infobox->id)
                ->whereNull('category_id')
                ->get();
        }

        // Choose template
        $template = $options['main_template'] ?? 'elfcms.public.infobox.main';

        page_config([
            'title' => $infobox->title,
            'description' => $infobox->meta_description,
            'keywords' => $infobox->meta_keywords,
        ]);

        return view($template, [
            'page' => $page,
            'infobox' => $infobox,
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    /**
     * Recursively loading child categories
     */
    protected function loadChildrenRecursive($query, int $depth)
    {
        if ($depth <= 0) {
            return;
        }

        $query->with(['children' => function ($q) use ($depth) {
            $this->loadChildrenRecursive($q, $depth - 1);
        }]);
    }

    public function showCategory(Page $page, InfoboxCategory $category)
    {
        if (empty($page->module_id) || $category->infobox_id != $page->module_id) {
            abort(404);
        }

        $options = $page->module_options ?? [];
        $template = $options['category_template'] ?? 'elfcms.public.infobox.category';

        page_config([
            'title' => $category->title,
            'infobox_title' => $category->infobox->title,
            'description' => $category->meta_description,
            'keywords' => $category->meta_keywords,
        ]);

        return view($template, [
            'page' => $page,
            'category' => $category,
        ]);
    }

    public function showItem(Page $page, InfoboxItem $item)
    {
        if (empty($page->module_id) || $item->infobox_id != $page->module_id) {
            abort(404);
        }

        $options = $page->module_options ?? [];
        $template = $options['item_template'] ?? 'elfcms.public.infobox.item';

        page_config([
            'title' => $item->title,
            'category_title' => $item?->category?->title ?? '',
            'infobox_title' => $item->infobox->title,
            'description' => $item->meta_description,
            'keywords' => $item->meta_keywords,
        ]);

        return view($template, [
            'page' => $page,
            'item' => $item,
        ]);
    }

    public function showCategoryItem(Page $page, InfoboxCategory $category, InfoboxItem $item)
    {
        if (empty($page->module_id) || $item->infobox_id != $page->module_id || $item->category_id != $category->id) {
            abort(404);
        }

        $options = $page->module_options ?? [];
        $template = $options['item_template'] ?? 'elfcms.public.infobox.item';

        page_config([
            'title' => $item->title,
            'category_title' => $category->title,
            'infobox_title' => $item->infobox->title,
            'description' => $item->meta_description,
            'keywords' => $item->meta_keywords,
        ]);

        return view($template, [
            'page' => $page,
            'item' => $item,
        ]);
    }

    public function createPage(Infobox $infobox)
    {
        try {

            $page = Page::where('module', 'infobox')->where('module_id', $infobox->id)->first();
            if (!empty($page) && !empty($page->id)) {
                return redirect()->back()->withErrors(['error' => __('elfcms::default.page_already_exists')]);
            }

            $data['title'] = $infobox->title;
            $data['content'] = null;
            $data['browser_title'] = $infobox->title;
            $data['active'] = 1;
            $data['name'] = $infobox->title;
            $data['slug'] = $infobox->slug;
            $data['module'] = 'infobox';
            $data['module_id'] = $infobox->id;
            $data['module_options'] = [
                'item_path' => null,
                'show_items' => 1,
                'category_path' => null,
                'item_template' => 'elfcms.public.infobox.item',
                'main_template' => 'elfcms.public.infobox.main',
                'show_categories' => 1,
                'categories_depth' => 1,
                'category_template' => 'elfcms.public.infobox.category',
                'use_category_path' => 1
            ];
            $data['path'] = $infobox->slug;
            $page = Page::create($data);

            if (!empty($page)) {
                return redirect(route('admin.page.pages.edit', $page))->with('success', __('elfcms::default.page_created_successfully'));
            }
            return redirect(route('admin.page.pages'))->with('success', __('elfcms::default.page_created_successfully'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => __('elfcms::default.error') . ': ' . $th->getMessage()]);
        }
    }
}
