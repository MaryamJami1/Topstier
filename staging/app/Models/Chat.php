<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class Chat extends Model
{
 	use PreventDemoModeChanges;
    protected $guarded = [];

    public function chat_thread()
    {
        return $this->belongsTo(ChatThread::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
