<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Language extends Model
{
 	use PreventDemoModeChanges;
}
