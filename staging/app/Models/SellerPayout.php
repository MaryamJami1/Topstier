<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class SellerPayout extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
