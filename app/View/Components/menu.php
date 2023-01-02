<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Connection;
use App\Models\User;

class menu extends Component
{
   public $suggestions_count,$requests_count,$received_count;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($suggestions_count,$requests_count,$received_count)
    {
        $this->suggestions_count = $suggestions_count;
        $this->requests_count = $requests_count;
        $this->received_count = $received_count;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu');
    }
}
