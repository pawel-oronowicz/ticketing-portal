<?php

namespace App\Models;

use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // @codeCoverageIgnoreStart

    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    // @codeCoverageIgnoreEnd
}
