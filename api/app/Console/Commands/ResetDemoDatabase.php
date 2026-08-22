<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('demo:reset')]
#[Description('Resets demo DB to default state')]
class ResetDemoDatabase extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (!app()->environment('production') || !config('features.demo_mode')) {
            $this->error('Demo reset only runs when APP_ENV=production and DEMO_MODE=true.');
            return;
        }

        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true, '--seeder' => 'DemoSeeder']);
        $this->info('Demo database reset complete.');
    }
}
