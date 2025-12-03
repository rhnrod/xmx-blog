<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public $showHeader;
    public $showFooter;

    public function __construct($showHeader = true, $showFooter = true)
    {
        // Converte strings "true"/"false" para boolean
        $this->showHeader = filter_var($showHeader, FILTER_VALIDATE_BOOLEAN);
        $this->showFooter = filter_var($showFooter, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('components.layout');
    }
}
