<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Zone extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;

    public function cities(){
        return $this->hasMany(City::class);
    }
}
