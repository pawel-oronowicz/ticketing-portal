<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    public function index()
    {
        $companies = $this->companyService->all();

        return response()->json(CompanyResource::collection($companies));
    }
}
