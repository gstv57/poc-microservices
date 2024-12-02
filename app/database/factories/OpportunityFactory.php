<?php

namespace Database\Factories;

use App\Enum\OpportunityStatus;
use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opportunity>
 */
class OpportunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence,
            'url'          => $this->faker->url,
            'details'      => $this->faker->paragraph,
            'business'     => $this->faker->company,
            'last_send_at' => $this->faker->dateTime,
            'status'       => OpportunityStatus::PENDING,
        ];
    }
}
