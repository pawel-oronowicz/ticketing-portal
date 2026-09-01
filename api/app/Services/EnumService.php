<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Cache;
use Illuminate\Http\JsonResponse;

class EnumService
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        return Cache::remember('enums', now()->addDays(30), function () {
            return [
                'ticket_statuses' => array_map(fn($case) => [
                    'value' => $case->value,
                    'label' => $case->label(),
                ], TicketStatus::cases()),
                'ticket_priorities' => array_map(fn($case) => [
                    'value' => $case->value,
                    'label' => $case->label(),
                ], TicketPriority::cases()),
            ];
        });
    }
}
