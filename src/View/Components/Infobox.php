<?php

namespace Elfcms\Infobox\View\Components;

use Elfcms\Infobox\Models\InfoboxItem;
use Illuminate\View\Component;

class Infobox extends Component
{
    public $item, $theme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $theme='default')
    {
        if (is_numeric($item)) {
            $item = intval($item);
            $item = InfoboxItem::with('options')->find($item);
        }
        elseif (gettype($item) == 'string') {
            $item = InfoboxItem::where('code',$item)->with('options')->first();
        }
        $this->item = $item;
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('infobox::components.box.'.$this->theme);
    }
}
