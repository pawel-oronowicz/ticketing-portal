<?php

namespace App\Repositories;

use App\Enums\UserRole;
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
     * @param UserRole|null $role
     * @return Collection
     */
    public function findAll(?UserRole $role = null): Collection
    {
        if($role) {
            return User::where('role', $role->value)->get();
        }

        return User::all();
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
