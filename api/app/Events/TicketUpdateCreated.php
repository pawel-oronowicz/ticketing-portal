<?php

namespace App\Events;

use App\Models\TicketUpdate;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketUpdateCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TicketUpdate $ticketUpdate) {}
}
