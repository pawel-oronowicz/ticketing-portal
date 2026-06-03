<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param Company $company
     * @return Collection
     */
    public function findAllByCompany(Company $company): Collection
    {
        return User::where('company_id', $company->id)->get();
    }
}
