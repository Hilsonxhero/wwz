<?php

namespace Modules\Banner\Enum;

enum BannerType: string
{
    case TOP = "top";
    case HEADER = "header";
    case HERO = "hero";
    case MIDDLE = "middle";
    case BOTTOM = "bottom";
}
