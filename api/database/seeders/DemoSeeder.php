<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo DB seed.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'Demo Admin',
            'email' => 'demoadmin@ticketingportal.com',
            'password' => Hash::make('demo1234'),
            'role' => UserRole::Admin,
        ]);

        $this->call([
            DatabaseSeeder::class,
        ]);
    }
}
