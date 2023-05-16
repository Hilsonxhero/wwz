<?php

namespace Modules\Payment\Enums;

enum PaymentMethodStatus: string
{
    case ENABLE = "enable";
    case DISABLE = "disable";
}
