<?php

use App\Enums\UserRole;
use App\Events\TicketUpdateCreated;
use App\Listeners\SendTicketUpdateNotification;
use App\Mail\TicketUpdateNotificationEmail;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;

test('notifies assigned engineer when customer posts update', function () {
    Mail::fake();

    $company = Company::factory()->make();
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer]);

    $ticket = new Ticket(['assigned_user_id' => $engineer->id]);
    $ticket->setRelation('assigned', $engineer);

    $ticketUpdate = new TicketUpdate(['is_internal' => false]);
    $ticketUpdate->setRelation('createdBy', $customer);
    $ticketUpdate->setRelation('ticket', $ticket);

    $listener = new SendTicketUpdateNotification();
    $listener->handle(new TicketUpdateCreated($ticketUpdate));

    Mail::assertQueued(TicketUpdateNotificationEmail::class, function ($mail) use ($engineer) {
        return $mail->hasTo($engineer->email);
    });
});

test('notifies customer when engineer posts a non-internal update', function () {
    Mail::fake();

    $company = Company::factory()->make();
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer]);

    $ticket = new Ticket(['assigned_user_id' => $engineer->id]);
    $ticket->created_by_user_id = $customer->id;
    $ticket->setRelation('createdBy', $customer);

    $ticketUpdate = new TicketUpdate(['is_internal' => false]);
    $ticketUpdate->created_by_user_id = $engineer->id;
    $ticketUpdate->setRelation('createdBy', $engineer);
    $ticketUpdate->setRelation('ticket', $ticket);

    $listener = new SendTicketUpdateNotification();
    $listener->handle(new TicketUpdateCreated($ticketUpdate));

    Mail::assertQueued(TicketUpdateNotificationEmail::class, function ($mail) use ($customer) {
        return $mail->hasTo($customer->email);
    });
});

test('does not notify anyone for internal updates', function () {
    Mail::fake();

    $company = Company::factory()->make();
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer]);

    $ticket = new Ticket(['assigned_user_id' => $engineer->id]);
    $ticket->created_by_user_id = $customer->id;
    $ticket->setRelation('createdBy', $customer);

    $ticketUpdate = new TicketUpdate(['is_internal' => true]);
    $ticketUpdate->created_by_user_id = $engineer->id;
    $ticketUpdate->setRelation('createdBy', $engineer);
    $ticketUpdate->setRelation('ticket', $ticket);

    $listener = new SendTicketUpdateNotification();
    $listener->handle(new TicketUpdateCreated($ticketUpdate));

    Mail::assertNothingQueued();
});
