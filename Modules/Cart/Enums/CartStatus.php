<?php

namespace Modules\Cart\Enums;

enum CartStatus: string
{
    case Available = "available";
    case Processed = "processed";
}
