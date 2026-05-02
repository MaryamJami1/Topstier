<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ShopFollower extends Model
{
 	use PreventDemoModeChanges;
    protected $fillable = [
        'user_id', 'shop_id'
    ];
}
