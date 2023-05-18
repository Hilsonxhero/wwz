<?php

namespace Modules\Payment\Enums;

enum PaymentMethodType: string
{
    case ONLINE = "online";
    case WALLET = "wallet";
}
