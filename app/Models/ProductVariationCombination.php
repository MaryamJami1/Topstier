<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ProductVariationCombination extends Model
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
    public function attribute_value()
    {
        return $this->belongsTo(AttributeValue::class);
    }
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class,'product_variation_id');
    }
}
