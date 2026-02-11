<?php

namespace App\Enums;

enum SystemPermisson: string
{
    case CREATE_CONTENT = 'create_content';
    case UPDATE_CONTENT = 'update_content';
    case DELETE_CONTENT = 'delete_content';
    case VIEW_CONTENT = 'view_content';
}
