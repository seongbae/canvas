<?php

namespace Seongbae\Canvas\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Seongbae\Canvas\Traits\HasInputAttributes;

class Textarea extends Component
{
    use HasInputAttributes;

    public $rows;
    public $value;
    public $readonly;
    public $required;

    public function __construct($name, $rows = 3, $required = false, $label = null, $id = null, $value = null, $hint = null, $disabled = false, $readonly = false)
    {
        $this->name = $name;
        $this->rows = $rows;
        $this->label = $label ?? Str::title(str_replace('_', ' ', $name));
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->hint = $hint;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->required = $required;
    }

    public function render()
    {
        return view('canvas::fields.textarea');
    }
}
