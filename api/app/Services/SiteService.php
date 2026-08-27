<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\SiteRepository;
use Illuminate\Database\Eloquent\Collection;

class SiteService
{
    public function __construct(private SiteRepository $siteRepository) {}

    /**
     * @param Company $company
     * @return Collection
     */
    public function findAllByCompany(Company $company): Collection
    {
        return $this->siteRepository->findAllByCompany($company);
    }
}
