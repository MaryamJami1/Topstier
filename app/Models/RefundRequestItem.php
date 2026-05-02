<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class RefundRequestItem extends Model
{
 	use PreventDemoModeChanges;
    use HasFactory;
    public function refundRequest()
    {
        return $this->belongsTo(RefundRequest::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
