<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\Payment;

class PaymentController extends Controller
{

    public function __construct(private PaymentService $paymentRepo) {}
    public function generateKHQR(Request $request)
    {
        $amount = $request->get('amount');

        $data = $this->paymentRepo->generateKHQR($amount);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function checkPaymentStatus(Payment $payment)
    {
        
        $data = $this->paymentRepo->checkPaymentStatus($payment->id);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
