<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketUpdateResource;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Services\TicketUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketUpdateController extends Controller
{
    public function __construct(private TicketUpdateService $ticketUpdateService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Ticket $ticket): JsonResponse
    {
        $ticketUpdates = $this->ticketUpdateService->findAllByTicket($ticket);

        return response()->json(TicketUpdateResource::collection($ticketUpdates));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketUpdate $ticketUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketUpdate $ticketUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketUpdate $ticketUpdate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketUpdate $ticketUpdate)
    {
        //
    }
}
