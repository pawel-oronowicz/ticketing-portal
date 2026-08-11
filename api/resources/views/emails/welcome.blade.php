<x-mail::message>
# Welcome, {{ $user->name }}!

Thanks for registering with the Ticketing Portal.

<x-mail::button :url="config('app.frontend_url')">
Go to Portal
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
