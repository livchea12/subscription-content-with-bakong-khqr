<?php

namespace App\Repository;

use App\Models\Payment;
use App\Repository\Interface\PaymentRepoInterface;
use App\Enums\PaymentStatus;

class PaymentRepo implements PaymentRepoInterface
{
    public function createPayment($userSubscriptionId, $transactionId, $amount)
    {
        return Payment::create([
            'user_subscription_id' => $userSubscriptionId,
            'provider' => 'Bakong',
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => 'USD',
            'status' => PaymentStatus::PENDING->value,
        ]);
    }

    public function updatePaymentStatus($paymentId, $status)
    {
        return Payment::where('id', $paymentId)->update(['status' => $status]);
    }
    public function getPaymentById($paymentId)
    {
        return Payment::where('id', $paymentId)->first();
    }
}
