<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ShopBrand extends Model
{
 	use PreventDemoModeChanges;
    protected $fillable = [
        'shop_id', 'brand_id'
    ];
}
