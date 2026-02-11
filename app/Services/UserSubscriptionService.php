<?php

namespace App\Services;

use App\Repository\Interface\UserSubscriptionRepoInterface;
use App\Models\SubscriptionPlan;

class UserSubscriptionService
{
    public function __construct(private UserSubscriptionRepoInterface $userSubscriptionRepo) {}

    public function subscribe($userId, $subscriptionPlanId)
    {

        $plan = SubscriptionPlan::findOrFail($subscriptionPlanId);
        $interval = $plan->interval;

        $expiredAt = match ($interval->value) {
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => throw new \Exception("Invalid subscription interval: {$interval}"),
        };

        return $this->userSubscriptionRepo->subscribe($userId, $subscriptionPlanId, $expiredAt);
    }
}
