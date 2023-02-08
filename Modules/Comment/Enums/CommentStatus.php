<?php

namespace Modules\Comment\Enums;

enum CommentStatus: string
{
    case Pending  = 'pending';
    case Approved = 'Approved';
    case Rejected = 'rejected';
}
