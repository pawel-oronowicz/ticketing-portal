<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Site>
 */
class SiteFactory extends Factory
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
            'company_id' => fake()->randomElement(Company::pluck('id')),
            'is_default' => false,
            'address_line1' => fake()->buildingNumber(),
            'address_line2' => fake()->streetName(),
            'postcode' => fake()->postcode(),
            'city' => fake()->city(),
            'country_id' => fake()->randomElement(Country::pluck('id')),
        ];
    }
}
