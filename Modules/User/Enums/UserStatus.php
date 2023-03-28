<?php

namespace Modules\User\Enums;

enum UserStatus: string
{
    case ACTIVE  = 'active';
    case INACTIVE = 'inactive';
    case BAN = 'ban';
}
