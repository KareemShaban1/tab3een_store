<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;

class FrontLayout extends Component
{
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title=null)
    {
        //
        // if title = null return config('app.name')
        $this->title = $title ?? config('app.name');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categories = Category::all();
        return view('frontend.layouts.front',compact('categories'));
    }
}