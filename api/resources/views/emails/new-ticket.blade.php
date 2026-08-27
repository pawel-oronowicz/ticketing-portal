<x-mail::message>
# Dear, {{ $user->name }}

A new ticket #{{ $ticket->id }} - "{{ $ticket->subject }}" has been created by {{ $ticket->createdBy->name }}.

<x-mail::button :url="config('app.frontend_url').'/tickets/'.$ticket->id">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
