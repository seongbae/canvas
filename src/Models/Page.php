<?php

namespace Seongbae\Canvas\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Page extends Model
{
    protected $fillable = [
    	'id',
        'title',
        'slug',
        'body',
        'user_id',
        'parent_id'
    ];

    protected $appends = ['url','uri'];

    public function getURIAttribute()
    {
        return $this->getURI($this);
        
    }

    public function getURLAttribute()
    {
        return url($this->uri);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    private function getURI($page)
    {
        if ($page->parent_id != null)
            return $this->getURI(Page::find($page->parent_id)) . '/' .$page->slug;
        else
            return $page->slug;
    }
}
