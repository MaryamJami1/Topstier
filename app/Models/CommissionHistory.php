<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class CommissionHistory extends Model
{
 	use PreventDemoModeChanges;
    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }
}
