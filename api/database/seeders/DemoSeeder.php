<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo DB seed.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'demoadmin@ticketingportal.com',
            'email_verified_at' => now(),
            'password' => Hash::make('demo1234'),
            'remember_token' => Str::random(10),
            'role' => UserRole::Admin,
        ]);

        $this->call([
            DatabaseSeeder::class,
        ]);
    }
}
