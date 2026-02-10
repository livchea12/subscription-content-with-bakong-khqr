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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
