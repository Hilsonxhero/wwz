<?php

namespace Modules\Order\Events\App;

use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use SerializesModels;

    public $order;
    public $order_shipping;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $order_shipping)
    {
        $this->order = $order;
        $this->order_shipping = $order_shipping;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
