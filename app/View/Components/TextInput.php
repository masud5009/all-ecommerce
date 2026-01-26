<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextInput extends Component
{
    public $col, $placeholder, $name, $type, $label, $required, $language, $dataInfo,$value,$action,$attribute;
    /**
     * Create a new component instance.
     */
    public function __construct($col = null, $type = null, $placeholder = null, $name = null, $label = null, $required = null, $language = null, $dataInfo = null,$value=null,$action = null,$attribute=null)
    {
        $this->col = $col;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->language = $language;
        $this->dataInfo = $dataInfo;
        $this->value = $value;
        $this->action = $action;
        $this->attribute = $attribute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.text-input');
    }
}
