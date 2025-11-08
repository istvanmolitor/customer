<?php

namespace Molitor\Customer\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Molitor\Customer\Models\Customer;

class CustomerDestroyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Customer $customer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}
