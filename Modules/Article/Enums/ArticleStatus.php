<?php

namespace Modules\Article\Enums;

enum ArticleStatus: string
{
    case Disable  = 'disable';
    case Enable = 'enable';
    case Pending = 'pending';
    case Rejected = 'rejected';
}
