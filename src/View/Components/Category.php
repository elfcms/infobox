<?php

namespace Elfcms\Infobox\View\Components;

use Elfcms\Infobox\Models\Infobox as ModelsInfobox;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Category extends Component
{
    public $category, $theme, $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($category, $theme = 'default', $params = [])
    {
        if (is_numeric($category)) {
            $category = intval($category);
            $category = ModelsInfobox::with('items')->find($category);
        }
        elseif (gettype($category) == 'string') {
            $infobox = ModelsInfobox::where('slug',$category)->with('categories')->with('items')->first();
        }
        $this->category = $category;
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
        if (View::exists('components.infobox.category.' . $this->theme)) {
            return view('components.infobox.category.' . $this->theme);
        }
        if (View::exists('infobox.components.category.' . $this->theme)) {
            return view('infobox.components.category.' . $this->theme);
        }
        if (View::exists('infobox::components.category.' . $this->theme)) {
            return view('infobox::components.category.' . $this->theme);
        }
        if (View::exists('elfcms.components.infobox.category.' . $this->theme)) {
            return view('elfcms.components.infobox.category.' . $this->theme);
        }
        if (View::exists('elfcms.infobox.components.category.' . $this->theme)) {
            return view('elfcms.infobox.components.category.' . $this->theme);
        }
        if (View::exists('elfcms.modules.infobox.components.category.' . $this->theme)) {
            return view('elfcms.modules.infobox.components.category.' . $this->theme);
        }
        if (View::exists('elfcms::infobox.components.category.' . $this->theme)) {
            return view('elfcms::infobox.components.category.' . $this->theme);
        }
        if (View::exists($this->theme)) {
            return view($this->theme);
        }
        return null;
    }
}
