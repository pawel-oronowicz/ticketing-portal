<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class SiteRepository
{
    /**
     * @param Company $company
     * @return Collection
     */
    public function findAllByCompany(Company $company): Collection
    {
        return Site::where('company_id', $company->id)
            ->orderBy('name')
            ->get();
    }
}
