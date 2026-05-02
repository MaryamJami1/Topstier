<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class AttributeValueTranslation extends Model
{
 	use PreventDemoModeChanges;
  protected $fillable = ['name', 'lang', 'attribute_value_id'];

  public function attribute_value(){
    return $this->belongsTo(AttributeValue::class);
  }
}
