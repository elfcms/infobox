<?php

namespace Elfcms\Infobox\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InfoboxNavigator extends Controller
{
    public function index(Request $request)
    {

        $message = null;

        if ($request->session()->has('categoryresult')) {
            $message = [
                'type' => 'alternate',
                'header' => '&nbsp;',
                'text' => Session::get('categoryresult'),
            ];
        }
        elseif ($request->session()->has('itemresult')) {
            $message = [
                'type' => 'alternate',
                'header' => '&nbsp;',
                'text' => Session::get('itemresult'),
            ];
        }
        elseif ($request->session()->has('errors')) {
            $text = '';
            foreach ($request->session()->get('errors')->getBags()['default']->toArray() as $key => $errors) {
                foreach ($errors as $error) {
                    $text .= '<li>'. $error .'</li>';
                }
            }
            $message = [
                'type' => 'danger',
                'header' => __('elfcms::default.errors'),
                'text' => '<ul>' . $text . '</ul>',
            ];
        }

        $infoboxes = Infobox::position()->get();

        $infobox = Infobox::where('slug', $request->infobox)->first() ?? new Infobox();

        $category = InfoboxCategory::where('slug', $request->category)->first() ?? null;

        if ($category) {
            $categories = $category->categories ?? [];
        }
        else {
            $categories = $infobox->topCategories ?? [];
        }

        $page = null;

        if (!empty($infobox) && !empty($infobox->id)) {
            $page = Page::where('module','infobox')->where('module_id',$infobox->id)->first();
        }
        return view('elfcms::admin.infobox.nav.index',[
            'page' => [
                'title' => empty($infobox->id) ? __('infobox::default.infoboxes') : __('infobox::default.infobox') . ': ' . $infobox->title,
                'current' => url()->current(),
            ],
            'infoboxes' => $infoboxes,
            'infobox' => $infobox,
            'category' => $category,
            'categories' => $categories,
            'message' => $message,
            'page' => $page,
        ]);
    }
}
