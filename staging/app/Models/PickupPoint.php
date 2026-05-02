<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupPoint extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->BelongsTo(User::class);
    }
}
