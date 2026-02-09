<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\UserSubscriptionStatus;
class UserSubscription extends Model
{
    /** @use HasFactory<\Database\Factories\UserSubscriptionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
    ];

    protected $casts = [
        'status' => UserSubscriptionStatus::class,
    ];
}
