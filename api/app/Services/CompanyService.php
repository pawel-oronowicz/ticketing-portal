<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function __construct(private CompanyRepository $companyRepository) {}

    public function findAll(): Collection
    {
        return $this->companyRepository->findAll();
    }
}
