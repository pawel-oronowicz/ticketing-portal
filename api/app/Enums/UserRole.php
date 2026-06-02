<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Engineer = 'engineer';
    case Customer = 'customer';
}
