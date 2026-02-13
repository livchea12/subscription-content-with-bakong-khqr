<?php

namespace App\Repository;

use App\Models\Payment;
use App\Repository\Interface\PaymentRepoInterface;
use App\Enums\PaymentStatus;
class PaymentRepo implements PaymentRepoInterface {
    public function createPayment($userSubscriptionId, $transactionId, $amount){
        return Payment::create([
            'user_subscription_id'=> $userSubscriptionId,
            'provider'=> 'Bakong',
            'transaction_id'=> $transactionId,
            'amount'=> $amount,
            'currency'=> 'USD',
            'status'=> PaymentStatus::PENDING->value,
        ]);

    }
}