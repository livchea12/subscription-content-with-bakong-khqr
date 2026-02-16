<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionPlanService;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct(private SubscriptionPlanService $subscriptionPlanService) {}

    public function index()
    {
        $plans = $this->subscriptionPlanService->getAllPlans();

        return response()->json([
            'status' => 'success',
            'data' => $plans
        ]);
    }
}
