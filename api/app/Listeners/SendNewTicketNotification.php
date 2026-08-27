<?php

namespace App\Listeners;

use App\Enums\UserRole;
use App\Events\TicketCreated;
use App\Mail\NewTicketNotificationEmail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendNewTicketNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        $recipient = $this->determineUser($ticket);

        if($recipient) {
            Mail::to($recipient)->queue(new NewTicketNotificationEmail($recipient, $ticket));
        }
    }

    /**
     * Determine which User to send the notification to
     *
     * @param Ticket $ticket
     * @return User|null
     */
    private function determineUser(Ticket $ticket): ?User
    {
        if($ticket->assigned) {
            return $ticket->assigned;
        }

        return null;
    }
}
