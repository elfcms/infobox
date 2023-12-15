<?php

namespace Elfcms\Infobox\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InfoboxNavigator extends Controller
{
    public function index(Request $request)
    {

        //dd(Session::get('categoryresult'));
        //dd($request->session());
        //dd(Session::get('errors')->getBags()['default']);
        //dd($request->session()->get('errors')->getBags()['default']->toArray());

        /* $trend = 'asc';
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

        } */
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

        /* $c = InfoboxCategory::find(6);

        dd($c->parentsId()); */

        //dd(InfoboxCategory::where('infobox_id', $infobox->id)->get()[0]->subtree());
        //dd(InfoboxCategory::flat()->where('infobox_id', $infobox->id));
        //dd($infobox->categories[0]->categories);
        //dd($infobox->topCategories);

        /* $pageTitle = __('infobox::default.infobox') . ': ' . $infobox->title;

        if ($category) {
            $pageTitle .= ' | ' . __('infobox::default.category') . ': ' . $category->title . ' sdfdsgdfsgsdf sdg sdfgsdf sdf gsfdgfdg';
        } */

        return view('elfcms::admin.infobox.nav.index',[
            'page' => [
                'title' => empty($infobox->id) ? __('infobox::default.infoboxes') : __('infobox::default.infobox') . ': ' . $infobox->title,
                'current' => url()->current(),
            ],
            'infoboxes' => $infoboxes,
            'infobox' => $infobox,
            'category' => $category,
            'categories' => $categories,
            'message' => $message
        ]);
    }
}
