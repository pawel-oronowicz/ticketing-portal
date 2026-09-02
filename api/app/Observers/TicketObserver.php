<?php

namespace App\Observers;

use App\Models\Ticket;
use Cache;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $this->flushCache();
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        $this->flushCache();
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        $this->flushCache();
    }

    /**
     * @return void
     */
    private function flushCache(): void
    {
        Cache::tags(['tickets'])->flush();
    }
}
