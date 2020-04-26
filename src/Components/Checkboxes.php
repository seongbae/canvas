<?php

namespace Seongbae\Canvas\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Seongbae\Canvas\Traits\FormatsOptions;
use Seongbae\Canvas\Traits\HasInputAttributes;

class Checkboxes extends Component
{
    use HasInputAttributes, FormatsOptions;

    public $options;
    public $value;

    public function __construct($name, $options, $label = null, $id = null, $value = null, $hint = null, $disabled = false)
    {
        $this->name = $name;
        $this->options = $this->formatOptions($options);
        $this->label = $label ?? Str::title(str_replace('_', ' ', $name));
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->hint = $hint;
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('canvas::checkboxes');
    }
}
