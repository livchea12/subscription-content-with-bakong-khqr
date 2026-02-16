<?php

namespace App\Repository\Interface;

interface PaymentRepoInterface
{
    public function createPayment($userSubscriptionId, $transactionId, $amount);
    public function updatePaymentStatus($paymentId, $status);
    public function getPaymentById($paymentId);
}
