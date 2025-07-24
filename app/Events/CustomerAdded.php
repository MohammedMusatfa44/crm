<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function broadcastOn()
    {
        return new Channel('customers');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->customer->id,
            'name' => $this->customer->full_name,
            'message' => 'تم إضافة عميل جديد!'
        ];
    }
}
