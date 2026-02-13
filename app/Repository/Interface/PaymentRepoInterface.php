<?php

namespace App\Repository\Interface;

interface PaymentRepoInterface {
    public function createPayment($userSubscriptionId, $transactionId, $amount);
}