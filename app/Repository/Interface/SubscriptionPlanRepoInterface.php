<?php

namespace App\Repository\Interface;
use App\Models\SubscriptionPlan;
interface SubscriptionPlanRepoInterface
{
    public function getAllPlans();

    public function getPlanDetail($planId): SubscriptionPlan;

}
