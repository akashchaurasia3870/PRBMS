<?php

namespace App\View\Components\roles;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class basic_info extends Component
{
    /**
     * Create a new component instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.roles.basic_info');
    }
}
