<?php

namespace App\Listeners;

use App\Enums\UserRole;
use App\Events\TicketUpdateCreated;
use App\Mail\TicketUpdateNotificationEmail;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendTicketUpdateNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TicketUpdateCreated $event): void
    {
        $ticketUpdate = $event->ticketUpdate;
        $ticket = $ticketUpdate->ticket;

        $recipient = $this->determineUser($ticketUpdate, $ticket);

        if($recipient) {
            Mail::to($recipient)->queue(new TicketUpdateNotificationEmail($recipient, $ticketUpdate, $ticket));
        }
    }

    /**
     * Determine which User to send the notification to
     *
     * @param TicketUpdate $ticketUpdate
     * @param Ticket $ticket
     * @return User|null
     */
    private function determineUser(TicketUpdate $ticketUpdate, Ticket $ticket): ?User
    {
        if($ticketUpdate->createdBy->role === UserRole::Customer) {
            return $ticket->assigned;
        }

        if(!$ticketUpdate->is_internal) {
            return $ticket->createdBy;
        }

        return null;
    }
}
