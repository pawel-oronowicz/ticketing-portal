<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        abort_if(Gate::denies('viewAny', Company::class), 404);

        $companies = $this->companyService->findAll();

        return response()->json(CompanyResource::collection($companies));
    }
}
