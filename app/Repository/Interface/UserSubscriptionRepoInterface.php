<?php

namespace App\Repository\Interface;

use App\Models\UserSubscription;
use DateTimeInterface;


interface UserSubscriptionRepoInterface
{
    public function subscribe(int $userId, int $subscriptionPlanId, DateTimeInterface $expiredAt): UserSubscription;
    public function updateStatus($userSubscriptionId, $status);

    public function getUserSubscription($userId): UserSubscription;
}
