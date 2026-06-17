<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private TicketService $ticketService) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        abort_if(Gate::denies('viewAny', Ticket::class), 404);

        $tickets = $this->ticketService->findForUser(auth()->user());

        return response()->json(TicketResource::collection($tickets));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->findById($id);

        abort_if(!$ticket || Gate::denies('view', $ticket), 404);

        return response()->json(TicketResource::make($ticket));
    }

    /**
     * @param UpdateTicketRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        $ticket = $this->ticketService->findById($id);

        abort_if(Gate::denies('update', $ticket), 404);

        $ticket = $this->ticketService->update($id, $request->validated(), auth()->user());

        return response()->json(TicketResource::make($ticket));
    }
}
