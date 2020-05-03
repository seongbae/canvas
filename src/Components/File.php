<?php

namespace Seongbae\Canvas\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Seongbae\Canvas\Traits\HasInputAttributes;

class File extends Component
{
    use HasInputAttributes;

    public $file_label;
    public $multiple;

    public function __construct($name, $label = null, $fileLabel = null, $id = null, $multiple = false, $hint = null, $disabled = false, $value = null, $path = null)
    {
        $this->name = $name;
        $this->label = $label ?? Str::title(str_replace('_', ' ', $name));
        $this->file_label = $fileLabel;
        $this->id = $id ?? $name;
        $this->multiple = $multiple;
        $this->hint = $hint;
        $this->disabled = $disabled;
        $this->value = $path . $value; // change later to account for different file types icon
    }

    public function render()
    {
        return view('canvas::file');
    }
}
