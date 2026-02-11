<?php

namespace App\Repository;

use App\Models\UserSubscription;
use App\Repository\Interface\UserSubscriptionRepoInterface;
use App\Enums\UserSubscriptionStatus;
use DateTimeInterface;

class UserSubscriptionRepo implements UserSubscriptionRepoInterface
{
    public function subscribe(int $userId, int $subscriptionPlanId, DateTimeInterface $expiredAt): UserSubscription
    {
        return UserSubscription::create([
            'user_id' => $userId,
            'subscription_plan_id' => $subscriptionPlanId,
            'status' => UserSubscriptionStatus::ACTIVE,
            'expired_at' => $expiredAt,
        ]);
    }
}
