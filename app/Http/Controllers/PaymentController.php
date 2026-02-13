<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
class PaymentController extends Controller
{

    public function __construct(private PaymentService $paymentRepo){}
    public function generateKHQR(Request $request){
        $amount = $request->get('amount');

        $data = $this->paymentRepo->generateKHQR($amount);

        return response()->json([
            'status'=>'success',
            'data'=> $data 
        ]);
    }
}
