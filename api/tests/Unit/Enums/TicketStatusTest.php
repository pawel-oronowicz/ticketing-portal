<?php

use App\Enums\TicketStatus;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

test('isFinalised returns true for closed tickets', function () {
    assertTrue(TicketStatus::Resolved->isFinalised());
    assertTrue(TicketStatus::Closed->isFinalised());
    assertTrue(TicketStatus::Cancelled->isFinalised());
});

test('isFinalised returns true for open tickets', function () {
    assertFalse(TicketStatus::New->isFinalised());
    assertFalse(TicketStatus::InProgress->isFinalised());
    assertFalse(TicketStatus::OnHold->isFinalised());
});

test('label returns a human-readable string', function () {
    expect(TicketStatus::InProgress->label())->toBe('In Progress')
        ->and(TicketStatus::OnHold->label())->toBe('On Hold');
});
