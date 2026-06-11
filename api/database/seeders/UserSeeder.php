<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(20)
            ->create(['role' => UserRole::Engineer]);

        User::factory()
            ->count(1000)
            ->create([
                'role' => UserRole::Customer,
                'company_id' => fn() => Company::inRandomOrder()->first()->id,
            ]);
    }
}
