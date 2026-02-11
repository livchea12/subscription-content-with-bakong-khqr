<?php

namespace App\Enums;

enum SystemRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';
}
