<?php

namespace App\Http\Controllers;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Http\JsonResponse;

class EnumController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'ticket_statuses' => array_map(fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ], TicketStatus::cases()),
            'ticket_priorities' => array_map(fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ], TicketPriority::cases()),
        ]);
    }
}
