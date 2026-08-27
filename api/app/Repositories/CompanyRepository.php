<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Company::orderBy('name')->get();
    }
}
