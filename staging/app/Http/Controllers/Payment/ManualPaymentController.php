<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ManualPaymentMethod;
use Illuminate\Support\Facades\Log;

class ManualPaymentController extends Controller
{
    public function index()
    {
        try {
            $splittedPaymentMethod = explode('-', session('payment_method'));
            $offlinePaymentId = array_pop($splittedPaymentMethod);
            $manualPaymentMethod = ManualPaymentMethod::find((int) $offlinePaymentId);

            session()->put('manualPaymentMethod', $manualPaymentMethod);

            // generate payment_details here.
            if (session('payment_type') == 'wallet_payment') {
                $payment_details = 'Paid via ' . $manualPaymentMethod->heading . ' for recharge';
            } else {
                session()->put("order_payment", "yes");
                $payment_details = 'Paid via ' . $manualPaymentMethod->heading . '';
            }

            return (new PaymentController)->payment_success($payment_details);
        } catch (\Throwable $th) {
            Log::error('not working pay...', [
                'error' => $th->getMessage(),
                'file'  => $th->getFile(),
                'line'  => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);
            return (new PaymentController)->payment_failed();
        }
    }
}
