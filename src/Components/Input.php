<?php

namespace Seongbae\Canvas\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Seongbae\Canvas\Traits\HasInputAttributes;

class Input extends Component
{
    use HasInputAttributes;

    public $type;
    public $value;
    public $readonly;
    public $required;

    public function __construct($name, $type = 'text', $required = false, $label = null, $id = null, $value = null, $hint = null, $disabled = false, $readonly = false, $autofocus=false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label ?? Str::title(str_replace('_', ' ', $name));
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->hint = $hint;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->autofocus = $autofocus;
        $this->required = $required;

    }

    public function render()
    {
        return view('canvas::fields.input');
    }
}
