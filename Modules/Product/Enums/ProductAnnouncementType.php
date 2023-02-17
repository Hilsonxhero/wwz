<?php

namespace Modules\Product\Enums;

enum ProductAnnouncementType: string
{
    case Availability  = 'availability';
    case Promotion = 'promotion';
}
