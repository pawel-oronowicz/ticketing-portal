<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tickets = $this->ticketService->findAll();

        return response()->json(TicketResource::collection($tickets));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->findById($id);

        return response()->json(TicketResource::make($ticket));
    }
}
