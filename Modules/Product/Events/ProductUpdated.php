<?php

namespace Modules\Product\Events;

use Illuminate\Queue\SerializesModels;

class ProductUpdated
{
    use SerializesModels;

    public $variant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($variant)
    {
        $this->variant = $variant;
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
