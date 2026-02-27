<?php

namespace App\Repository;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
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
            'status' => UserSubscriptionStatus::PENDING_PAYMENT,
            'expired_at' => $expiredAt,
        ]);
    }

    public function updateStatus($userSubscriptionId, $status)
    {
        return UserSubscription::where('id', $userSubscriptionId)->update(['status' => $status]);
    }

    public function getUserSubscription($userId): UserSubscription
    {
        return UserSubscription::where('user_id', $userId)->firstOrFail();
    }
}
