<?php

namespace App\Services;

use App\Repository\Interface\UserSubscriptionRepoInterface;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
class UserSubscriptionService
{
    public function __construct(private UserSubscriptionRepoInterface $userSubscriptionRepo)
    {
    }

    public function subscribe($userId, $subscriptionPlanId)
    {

        $plan = SubscriptionPlan::findOrFail($subscriptionPlanId);
        $existingPlan = UserSubscription::where('user_id', $userId)
            ->where('status', 'active')->first();

        if($existingPlan){
            return false;
        }    
        $interval = $plan->interval;

        $expiredAt = match ($interval->value) {
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => throw new \Exception("Invalid subscription interval: {$interval}"),
        };

        return $this->userSubscriptionRepo->subscribe($userId, $subscriptionPlanId, $expiredAt);
    }
}
