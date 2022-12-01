<?php

namespace Modules\Payment\Enums;

enum PaymentStatus: string
{
    case Pending  = 'pending';
    case Success = 'success';
    case Canceled = 'canceled';
    case Rejected = 'rejected';
}
