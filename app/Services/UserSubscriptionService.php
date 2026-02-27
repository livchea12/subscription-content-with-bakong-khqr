<?php

namespace App\Services;

use App\Repository\Interface\PaymentRepoInterface;
use App\Repository\Interface\UserSubscriptionRepoInterface;
use App\Repository\Interface\SubscriptionPlanRepoInterface;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use KHQR\Models\SourceInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Jobs\CheckBakongPaymentStatus;

class UserSubscriptionService
{
    public function __construct(private UserSubscriptionRepoInterface $userSubscriptionRepo, private PaymentRepoInterface $paymentRepo, private SubscriptionPlanRepoInterface $subscriptionPlanRepo)
    {
    }

    public function subscribe($userId, $subscriptionPlanId)
    {

        //create user subscription record
        return DB::transaction(function () use ($userId, $subscriptionPlanId) {
            //create user subscription record
            $plan = SubscriptionPlan::findOrFail($subscriptionPlanId);
            $existingPlan = UserSubscription::where('user_id', $userId)
                ->where('status', 'active')->first();

            if ($existingPlan) {
                return false;
            }
            $interval = $plan->interval;

            $expiredAt = match ($interval->value) {
                'monthly' => now()->addMonth(),
                'yearly' => now()->addYear(),
                default => throw new \Exception("Invalid subscription interval: {$interval}"),
            };

            $userSubscriptionPlan = $this->userSubscriptionRepo->subscribe($userId, $subscriptionPlanId, $expiredAt);

            //generate qr code
            $individualInfo = new IndividualInfo(
                bakongAccountID: config('khqr.merchant_id'),
                merchantName: config('khqr.merchant_name'),
                merchantCity: 'PHNOM PENH',
                currency: KHQRData::CURRENCY_USD,
                amount: $plan->price,
            );
            $khqr = BakongKHQR::generateIndividual($individualInfo);

            $qr = isset($khqr->data['qr']) ? $khqr->data['qr'] : null;
            $md5 = isset($khqr->data['md5']) ? $khqr->data['md5'] : null;

            //generate deeplink
            $sourceInfo = new SourceInfo(
                appIconUrl: config('khqr.app_icon_url'),
                appName: 'Bakong',
                appDeepLinkCallback: config('khqr.bakong_api_url')
            );

            $deepLink = BakongKHQR::generateDeepLink($qr, $sourceInfo);

            //create payment record
            $payment = $this->paymentRepo->createPayment($userSubscriptionPlan->id, $md5, $plan->price);

            CheckBakongPaymentStatus::dispatch($payment->id);
            // Return qr, md5, deeplink, and payment id     
            return [
                'qr' => $qr,
                'md5' => $md5,
                'deeplink' => $deepLink,
                'payment_id' => $payment->id,
            ];
        });
    }

    public function updateStatus($userSubscriptionId, $status)
    {
        return $this->userSubscriptionRepo->updateStatus($userSubscriptionId, $status);
    }


    public function getSubscribePlanState(User $user)
    {
        $userSubscription = $this->userSubscriptionRepo->getUserSubscription($user->id);
        if (!$userSubscription) {
            return null;
        }

        $subscriptionPlan = $this->subscriptionPlanRepo->getPlanDetail($userSubscription->subscription_plan_id);

        return [
            'userSubscription' => $userSubscription,
            'subscriptionPlan' => $subscriptionPlan,
        ];
    }
}
