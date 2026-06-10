<?php

namespace Database\Factories;

use App\Models\TicketUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketUpdate>
 */
class TicketUpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->text(300),
            'ticket_id' => null,
            'is_internal' => fake()->boolean,
            'created_by_user_id' => null,
        ];
    }
}
