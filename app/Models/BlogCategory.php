<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class BlogCategory extends Model
{
 	use PreventDemoModeChanges;
  protected $with = ['blog_category_translations'];

  public function getTranslation($field = '', $lang = false)
  {
    $lang = $lang == false ? App::getLocale() : $lang;
    $blog_category_translation = $this->blog_category_translations->where('lang', $lang)->first();
    return $blog_category_translation != null ? $blog_category_translation->$field : $this->$field;
  }

  public function blog_category_translations()
  {
    return $this->hasMany(BlogCategoryTranslation::class);
  }

    public function blogs()
    {
        return $this->hasMany(Blog::class,'category_id');
    }
    
}
