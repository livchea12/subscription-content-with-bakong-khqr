<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Repository\Interface\AuthRepoInterface;
use App\Repository\AuthRepo;
use App\Repository\UserSubscriptionRepo;
use Illuminate\Support\Facades\Gate;
use App\Repository\Interface\UserSubscriptionRepoInterface;
use App\Repository\Interface\ContentRepoInterface;
use App\Repository\ContentRepo;
use App\Repository\Interface\PaymentRepoInterface;
use App\Repository\PaymentRepo;
use App\Repository\Interface\SubscriptionPlanRepoInterface;
use App\Repository\SubscriptionPlanRepo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepoInterface::class, AuthRepo::class);
        $this->app->bind(ContentRepoInterface::class, ContentRepo::class);
        $this->app->bind(UserSubscriptionRepoInterface::class, UserSubscriptionRepo::class);
        $this->app->bind(PaymentRepoInterface::class, PaymentRepo::class);
        $this->app->bind(SubscriptionPlanRepoInterface::class, SubscriptionPlanRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
