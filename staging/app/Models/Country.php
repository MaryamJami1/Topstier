<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Country extends Model
{
 	use PreventDemoModeChanges;
	public function states(){
        return $this->hasMany(State::class);
    }
}
