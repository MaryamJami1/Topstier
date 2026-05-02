<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App\Models\User;

class Wallet extends Model
{
 	use PreventDemoModeChanges;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
