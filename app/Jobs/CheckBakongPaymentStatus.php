<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\UserSubscriptionService;
use App\Enums\UserSubscriptionStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;

class CheckBakongPaymentStatus implements ShouldQueue
{
    use Queueable;

    public $tries = 100; // Allow multiple retries for pending payment
    public $backoff = 10; // Wait 10 seconds between retries

    /**
     * Create a new job instance.
     */
    public function __construct(public $paymentId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService, UserSubscriptionService $userSubscriptionService): void
    {
        $payment = Payment::findOrFail($this->paymentId);

        // Check for timeout (10 minutes)
        if ($payment->created_at->addMinutes(10)->isPast()) {
            $paymentService->updatePaymentStatus($this->paymentId, PaymentStatus::FAILED->value);
            $userSubscriptionService->updateStatus($payment->user_subscription_id, UserSubscriptionStatus::INACTIVE->value);
            return;
        }

        // Check payment status 
        $response = $paymentService->checkPaymentStatus($this->paymentId);

        $userSubscriptionId = $payment->user_subscription_id;

        Log::info("Checking Job for Payment ID: " . $this->paymentId);
        Log::info("userSubscriptionId found: " . ($userSubscriptionId ?? 'NULL'));

        $responseCode = $response['responseCode'] ?? 'MISSING';
        Log::info("Bakong Response Code: " . $responseCode);

        if ($responseCode == 0) {
            Log::info("Payment completed");
            $userSubscriptionService->updateStatus($userSubscriptionId, UserSubscriptionStatus::ACTIVE->value);
            $paymentService->updatePaymentStatus($this->paymentId, PaymentStatus::COMPLETED->value);
        } else if ($responseCode == 1) {
            Log::info("Payment pending");
            $this->release(10); // retry after 10 seconds
        } else {
            Log::info("Payment failed");
            $paymentService->updatePaymentStatus($this->paymentId, PaymentStatus::FAILED->value);
            $userSubscriptionService->updateStatus($userSubscriptionId, UserSubscriptionStatus::INACTIVE->value);
        }
    }
}
