<?php

namespace Modules\Product\Enums;

enum ProductStatus: string
{
    case ENABLE = "enable";
    case DISABLE = "disable";
    case PENDING = "pending";
    case REJECTED = "rejected";
}
