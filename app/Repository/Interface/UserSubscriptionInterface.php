<?php

namespace App\Repository\Interface;

use App\Models\UserSubscription;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserSubscriptionInterface
{
    public function subscribe(int $userId, int $subscriptionPlanId): UserSubscription;
}
