<?php

namespace App\Repository;

use App\Models\SubscriptionPlan;
use App\Repository\Interface\SubscriptionPlanRepoInterface;

class SubscriptionPlanRepo implements SubscriptionPlanRepoInterface
{
    public function getAllPlans()
    {
        return SubscriptionPlan::all();
    }

    public function getPlanDetail($planId): SubscriptionPlan{
        return SubscriptionPlan::find($planId);
    }

}
