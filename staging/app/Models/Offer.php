<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Offer extends Model
{
 	use PreventDemoModeChanges;
    public function offer_products()
    {
        return $this->hasMany(OfferProduct::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products');
    }
}
