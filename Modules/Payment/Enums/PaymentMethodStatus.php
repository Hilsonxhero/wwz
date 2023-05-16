<?php

namespace Modules\Payment\Enums;

enum GatewayStatus: string
{
    case ENABLE = "enable";
    case DISABLE = "disable";
}
