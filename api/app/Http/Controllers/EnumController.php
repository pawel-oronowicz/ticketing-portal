<?php

namespace App\Http\Controllers;

use App\Services\EnumService;
use Illuminate\Http\JsonResponse;

class EnumController extends Controller
{
    public function __construct(private readonly EnumService $enumService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->enumService->getAll());
    }
}
