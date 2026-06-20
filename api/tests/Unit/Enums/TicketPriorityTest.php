<?php

use App\Enums\TicketPriority;

test('label returns a human-readable string', function () {
    expect(TicketPriority::High->label())->toBe('High')
        ->and(TicketPriority::Medium->label())->toBe('Medium');
});
