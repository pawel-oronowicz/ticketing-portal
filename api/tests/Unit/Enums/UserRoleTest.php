<?php

use App\Enums\UserRole;

test('isInternal returns correct value', function () {
    expect(UserRole::Engineer->isInternal())->toBeTrue()
        ->and(UserRole::Admin->isInternal())->toBeTrue()
        ->and(UserRole::Customer->isInternal())->toBeFalse();
});
