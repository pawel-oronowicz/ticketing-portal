<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTicketUpdateRequest;
use App\Http\Resources\TicketUpdateResource;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Services\TicketUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketUpdateController extends Controller
{
    public function __construct(private TicketUpdateService $ticketUpdateService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Ticket $ticket): JsonResponse
    {
        abort_if(Gate::denies('view', $ticket), 404);

        $ticketUpdates = $this->ticketUpdateService->findAllByTicket($ticket, auth()->user());

        return response()->json(TicketUpdateResource::collection($ticketUpdates));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostTicketUpdateRequest $request, Ticket $ticket)
    {
        abort_if(Gate::denies('view', $ticket), 404);
        abort_if(Gate::denies('create', TicketUpdate::class), 404);

        $ticketUpdate = $this->ticketUpdateService->createTicketUpdate($ticket, $request->validated(), auth()->user());

        return response()->json(TicketUpdateResource::make($ticketUpdate), 201);
    }
}
