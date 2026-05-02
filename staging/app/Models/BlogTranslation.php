<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class BlogTranslation extends Model
{
 	use PreventDemoModeChanges;
    protected $fillable = ['title', 'short_description', 'description', 'lang', 'blog_id'];

    public function blog(){
        return $this->belongsTo(Blog::class);
    }
}
