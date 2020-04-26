<?php

namespace Seongbae\Canvas\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Media extends Model
{
    use HasTags;

    public function getTags()
	{
		return implode(", ", $this->tags->pluck('name')->toArray());
	}
}
