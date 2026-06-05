<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function index()
    {
        $tickets = $this->ticketService->findAll();

        return response()->json(TicketResource::collection($tickets));
    }
}
