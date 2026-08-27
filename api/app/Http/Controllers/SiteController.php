<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
use App\Services\SiteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SiteController extends Controller
{
    public function __construct(private SiteService $siteService) {}

    /**
     * @param int $companyId
     * @return JsonResponse
     */
    public function findAllByCompany(int $companyId): JsonResponse
    {
        abort_if(Gate::denies('viewAny', Site::class), 404);

        $sites = $this->siteService->findAllByCompany($companyId);

        return response()->json(SiteResource::collection($sites));
    }
}
