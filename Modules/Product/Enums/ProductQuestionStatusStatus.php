<?php

namespace Modules\Product\Enums;

enum ProductQuestionStatusStatus: string
{
    case Pending  = 'pending';
    case Approved = 'Approved';
    case Rejected = 'rejected';
}
