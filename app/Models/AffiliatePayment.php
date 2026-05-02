<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class AffiliatePayment extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;
}
