<x-mail::message>
# Dear, {{ $user->name }}

A new update has been created on ticket #{{ $ticket->id }} by {{ $ticketUpdate->createdBy->name }}:

<p>
{{ $ticketUpdate->text }}
</p>

<x-mail::button :url="config('app.frontend_url').'/tickets/'.$ticket->id">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
