<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ProductAttributeValue extends Model
{
 	use PreventDemoModeChanges;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
