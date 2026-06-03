<?php

namespace App\Enums;

enum TicketStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case OnHold = 'on_hold';
    case Resolved = 'resolved';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::New => 'New',
            self::InProgress => 'In Progress',
            self::OnHold => 'On Hold',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * @return bool
     */
    public function isFinalised(): bool
    {
        return in_array($this, [self::Resolved, self::Closed, self::Cancelled]);
    }
}
