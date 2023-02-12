<?php

namespace Modules\Comment\Enums;

enum CommentStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
