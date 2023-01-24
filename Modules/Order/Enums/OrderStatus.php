<?php

namespace Modules\Order\Enums;

enum OrderStatus: string
{
    case WaitPayment  = 'wait_payment';
    case Accepted = 'accepted';
    case Confirmation = 'confirmation';
    case Processed = 'processed';
    case LeavingCenter = 'leaving_center';
    case ReceivedCenter = 'received_center';
    case DeliveryDispatcher = 'delivery_dispatcher';
    case DeliveryCustomer = 'delivery_customer';
    case CanceledSystem = 'canceled_system';
}
