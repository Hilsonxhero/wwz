<?php

namespace Modules\State\Enums;

enum CityStatus: string
{
    case Disable  = 'disable';
    case Enable = 'enable';
    case Pending = 'pending';
    case Rejected = 'rejected';
}
