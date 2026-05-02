<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Setting extends Model
{
 	use PreventDemoModeChanges;
    //
}
