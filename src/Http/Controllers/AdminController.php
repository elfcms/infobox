<?php

namespace Elfcms\Infobox\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function infobox()
    {
        /* $parentMenuData = config('elfcms.blog.menu');
        $menuData = [];
        foreach ($parentMenuData[0]['submenu'] as $key => $data) {
            $subdata = [];
            $text = '';

            if ($data['route'] == 'admin.blog.categories') {
                $categoriesCount = BlogCategory::count();
                $inactiveCategoriesCount = BlogCategory::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('elfcms::default.categories'),
                    'value' => $categoriesCount . ' (' . $inactiveCategoriesCount . ' ' . __('elfcms::default.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.posts') {
                $postsCount = BlogPost::count();
                $inactivePostsCount = BlogPost::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('elfcms::default.posts'),
                    'value' => $postsCount . ' (' . $inactivePostsCount . ' ' . __('elfcms::default.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.comments') {
                $commentsCount = BlogComment::count();
                $subdata[] = [
                    'title' => __('elfcms::default.comments'),
                    'value' => $commentsCount
                ];
            }

            if ($data['route'] == 'admin.blog.likes') {
                $likesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('elfcms::default.likes'),
                    'value' => $likesCount
                ];
            }

            if ($data['route'] == 'admin.blog.votes') {
                $votesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('elfcms::default.votes'),
                    'value' => $votesCount
                ];
            }

            if ($data['route'] == 'admin.blog.tags') {
                $tagsCount = BlogTag::count();
                $subdata[] = [
                    'title' => __('elfcms::default.tags'),
                    'value' => $tagsCount
                ];
            }
            $menuData[$key] = $data;

            $menuData[$key]['subdata'] = $subdata;
            $menuData[$key]['text'] = $text;

        } */
        return view('elfcms::admin.infobox.index',[
            'page' => [
                'title' => 'Infobox',
                'current' => url()->current(),
            ],
            //'menuData' => $menuData,
        ]);
    }

}
