<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Engineer = 'engineer';
    case Customer = 'customer';

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return in_array($this, [self::Admin, self::Engineer]);
    }
}
