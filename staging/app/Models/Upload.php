<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Upload extends Model
{
 	use PreventDemoModeChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_original_name', 'file_name', 'user_id', 'extension', 'type', 'file_size',
    ];
}
