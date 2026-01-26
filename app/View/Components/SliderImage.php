<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SliderImage extends Component
{
    public $noteText;
    public $label;
    public $sliders;
    /**
     * Create a new component instance.
     */
    public function __construct($noteText=null,$label=null,$sliders=null)
    {
        $this->noteText = $noteText;
        $this->label = $label;
        $this->sliders = $sliders;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.slider-image');
    }
}
