<?php

namespace Elfcms\Infobox\View\Components;

use Elfcms\Infobox\Models\Infobox as ModelsInfobox;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Infobox extends Component
{
    public $infobox, $theme, $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($infobox, $theme='default', $params = [])
    {
        if (is_numeric($infobox)) {
            $infobox = intval($infobox);
            $infobox = ModelsInfobox::with('items')->find($infobox);
        }
        elseif (gettype($infobox) == 'string') {
            $infobox = ModelsInfobox::where('slug',$infobox)->with('categories')->with('items')->first();
        }
        $this->infobox = $infobox;
        $this->theme = $theme;
        $this->params = $params;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.infobox.' . $this->theme)) {
            return view('components.infobox.' . $this->theme);
        }
        if (View::exists('infobox.components.infobox.' . $this->theme)) {
            return view('infobox.components.infobox.' . $this->theme);
        }
        if (View::exists('infobox::components.infobox.' . $this->theme)) {
            return view('infobox::components.infobox.' . $this->theme);
        }
        if (View::exists('elfcms.components.infobox.' . $this->theme)) {
            return view('elfcms.components.infobox.' . $this->theme);
        }
        if (View::exists('elfcms.infobox.components.infobox.' . $this->theme)) {
            return view('elfcms.infobox.components.infobox.' . $this->theme);
        }
        if (View::exists('elfcms.modules.infobox.components.infobox.' . $this->theme)) {
            return view('elfcms.modules.infobox.components.infobox.' . $this->theme);
        }
        if (View::exists('elfcms::infobox.components.infobox.' . $this->theme)) {
            return view('elfcms::infobox.components.infobox.' . $this->theme);
        }
        if (View::exists($this->theme)) {
            return view($this->theme);
        }
        return null;
    }
}
