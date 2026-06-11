<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
        ];
    }

    /**
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Company $company) {
            Site::factory()
                ->count(rand(1, 5))
                ->for($company)
                ->create();
        });
    }
}
