<?php

namespace App\Services;

use App\Repository\Interface\SubscriptionPlanRepoInterface;

class SubscriptionPlanService
{
    public function __construct(private SubscriptionPlanRepoInterface $subscriptionPlanRepo) {}

    public function getAllPlans()
    {
        return $this->subscriptionPlanRepo->getAllPlans();
    }
}
