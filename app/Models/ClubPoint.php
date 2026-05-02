<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ClubPoint extends Model
{
 	use PreventDemoModeChanges;
    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function combined_order(){
    	return $this->belongsTo(CombinedOrder::class);
    }
}
