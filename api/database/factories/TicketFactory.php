<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected int $updateCount = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::inRandomOrder()->first();
        $createdBy = User::where('company_id', $company->id)->inRandomOrder()->first();
        $assigned = User::where('role', UserRole::Engineer)->inRandomOrder()->first();

        if($createdBy === null) {
            $createdBy = $assigned;
        }
        $site = Site::where('company_id', $company->id)->inRandomOrder()->first();

        return [
            'subject' => fake()->sentence(),
            'created_by_user_id' => $createdBy->id,
            'assigned_user_id' => $assigned->id,
            'status' => fake()->randomElement(TicketStatus::cases()),
            'priority' => fake()->randomElement(TicketPriority::cases()),
            'company_id' => $company->id,
            'site_id' => $site->id,
        ];
    }

    /**
     * Attach ticket updates after creation. Omit $count for a random 1-5, pass 0 for none.
     * @param int|null $count
     * @return TicketFactory
     */
    public function withUpdates(?int $count = null): static
    {
        return $this->afterCreating(function (Ticket $ticket) use ($count) {
            $actualCount = $count ?? rand(1, 5);

            if ($actualCount === 0) {
                return;
            }

            TicketUpdate::factory()
                ->count($actualCount)
                ->for($ticket)
                ->create([
                    'is_internal' => false,
                    'created_by_user_id' => $ticket->assigned_user_id !== null
                        ? fake()->randomElement([$ticket->created_by_user_id, $ticket->assigned_user_id])
                        : $ticket->created_by_user_id,
                ]);
        });
    }

    /**
     * @return $this
     */
    public function withoutUpdates(): static
    {
        return $this->withUpdates(0);
    }
}
