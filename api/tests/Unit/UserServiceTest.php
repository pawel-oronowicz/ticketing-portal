<?php

use App\Repositories\UserRepository;
use App\Services\UserService;

test('returns null when email not found', function () {
    $repository = mock(UserRepository::class);
    $repository->shouldReceive('findByEmail')->once()->andReturn(null);

    $service = new UserService($repository);
    $result = $service->authenticateUser(['email' => 'johndoe123@example.com', 'password' => 'password']);
    expect($result)->toBeNull();
});
