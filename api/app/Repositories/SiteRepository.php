<?php

namespace App\Repositories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;

class SiteRepository
{
    /**
     * @param int $companyId
     * @return Collection
     */
    public function findAllByCompany(int $companyId): Collection
    {
        return Site::where('company_id', $companyId)
            ->orderBy('name')
            ->get();
    }
}
