<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param array $data
     * @return User|null
     */
    public function authenticateUser(array $data): ?User
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if(!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * @param Company $company
     * @return Collection
     */
    public function findAllByCompany(Company $company): Collection
    {
        return $this->userRepository->findAllByCompany($company);
    }
}
