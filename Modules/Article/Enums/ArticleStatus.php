<?php

namespace Modules\Article\Enums;

enum ArticleStatus: string
{
    case DISABLE  = 'disable';
    case ENABLE = 'enable';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
}
