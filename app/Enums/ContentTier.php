<?php

namespace App\Enums;

enum ContentTier: string
{
    case FREE = 'free';

    case PLUS = 'plus';
    case PREMIUM = 'premium';

}