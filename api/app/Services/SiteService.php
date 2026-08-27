<?php

namespace App\Services;

use App\Repositories\SiteRepository;
use Illuminate\Database\Eloquent\Collection;

class SiteService
{
    public function __construct(private SiteRepository $siteRepository) {}

    /**
     * @param int $companyId
     * @return Collection
     */
    public function findAllByCompany(int $companyId): Collection
    {
        return $this->siteRepository->findAllByCompany($companyId);
    }
}
