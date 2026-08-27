<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\SiteResource;
use App\Models\Company;
use App\Models\Site;
use App\Services\SiteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SiteController extends Controller
{
    public function __construct(private SiteService $siteService) {}

    /**
     * @param Company $company
     * @return JsonResponse
     */
    public function index(Company $company): JsonResponse
    {
        abort_if(Gate::denies('viewAny', Site::class), 404);
        abort_if(
            auth()->user()->role === UserRole::Customer &&
            auth()->user()->company->id !== $company->id
            , 404
        );

        $sites = $this->siteService->findAllByCompany($company);

        return response()->json(SiteResource::collection($sites));
    }
}
