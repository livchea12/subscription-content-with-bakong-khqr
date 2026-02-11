<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Services\UserSubscriptionService;

class UserSubscriptionController extends Controller
{
    public function __construct(private UserSubscriptionService $userSubscriptionService) {}

    public function subscribe(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $user = $request->user();
        $subscriptionPlanId = $subscriptionPlan->id;
        $this->userSubscriptionService->subscribe($user->id, $subscriptionPlanId);
        return response()->json([
            'status'=> 'success',
            'message' => 'Subscribed successfully',
        ]);
    }
}
