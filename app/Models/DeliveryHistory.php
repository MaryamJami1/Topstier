<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class DeliveryHistory extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
