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
     * Attach sites after creation. Omit $count for a random 1-5, pass 0 for none.
     * @param int|null $count
     * @return CompanyFactory
     */
    public function withSites(?int $count = null): static
    {
        return $this->afterCreating(function (Company $company) use ($count) {
            $actualCount = $count ?? rand(1, 5);

            if ($actualCount === 0) {
                return;
            }

            Site::factory()
                ->count($actualCount)
                ->for($company)
                ->create();
        });
    }
}
