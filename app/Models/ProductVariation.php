<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ProductVariation extends Model
{
 	use PreventDemoModeChanges;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function combinations()
    {
        return $this->hasMany(ProductVariationCombination::class);
    }

    // protected $priceAttributes = ['price'];
    // public function getAttributeValue($key)
    // {
    //     $value = parent::getAttributeValue($key);

    //     if (in_array($key, $this->priceAttributes)) {
    //         return convert_price2($value);
    //     }

    //     return $value;
    // }
    // public function setAttribute($key, $value)
    // {
    //     if (in_array($key, $this->priceAttributes)) {
    //         $value = convert_to_usd($value);
    //     }

    //     return parent::setAttribute($key, $value);
    // }
}
