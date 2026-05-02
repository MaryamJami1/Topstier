<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class BlogCategoryTranslation extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;
    protected $fillable = ['name', 'lang', 'blog_category_id'];

    public function blog_category(){
        return $this->belongsTo(BlogCategory::class);
    }
}
