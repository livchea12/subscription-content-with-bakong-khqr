<?php

namespace App\Enums;

enum UserSubscriptionStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case INACTIVE = 'inactive';
    case PENDING_PAYMENT = 'pending_payment';
}
    