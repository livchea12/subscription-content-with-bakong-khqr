<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_subscription_id',
        'provider',
        'transaction_id',
        'amount',
        'currency',
        'status',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
    ];
}
