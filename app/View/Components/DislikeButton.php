<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DislikeButton extends Component
{
    public $postId;
    
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partials.dislike');
    }
}
