<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CompanyFactory;

#[Fillable(['name'])]
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;
}
