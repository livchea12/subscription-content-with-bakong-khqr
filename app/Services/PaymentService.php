<?php

namespace App\Services;

use App\Repository\Interface\PaymentRepoInterface;
use KHQR\BakongKHQR;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
class PaymentService
{
    private $bakongKhqr;

    public function __construct(private PaymentRepoInterface $paymentRepo)
    {
        $this->bakongKhqr = new BakongKHQR(config('khqr.api_token'));
    }

    public function checkPaymentStatus($paymentId)
    {
        $payment = $this->paymentRepo->getPaymentById($paymentId);
        log::info ("MD5: $payment->transaction_id");
        $response = $this->bakongKhqr->checkTransactionByMD5($payment->transaction_id);
        $responseCode = $response['responseCode'] ?? null;

        log::info("Respone code: $responseCode");
        

        return $response;   
    }

    public function updatePaymentStatus($paymentId, $status)
    {
        return $this->paymentRepo->updatePaymentStatus($paymentId, $status);
    }
}
