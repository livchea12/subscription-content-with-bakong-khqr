<?php

namespace App\Services;

use App\Repository\Interface\PaymentRepoInterface;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
class PaymentService {
    public function __construct(private PaymentRepoInterface $paymentRepo){}

    public function createPayment($userSubscriptionId, $transactionId, $amount){

    }


    public function generateKHQR($amount){

        $individualInfo = new IndividualInfo(
            bakongAccountID: config('khqr.merchant_id'),
            merchantName: config('khqr.merchant_name'),
            merchantCity: 'PHNOM PENH',
            currency: KHQRData::CURRENCY_USD,
            amount: $amount
        );
        $khqr = BakongKHQR::generateIndividual($individualInfo);
        return $khqr;
    }
}

