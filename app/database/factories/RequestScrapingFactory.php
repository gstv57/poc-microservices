<?php

namespace Database\Factories;

use App\Enum\RequestScrapingStatus;
use App\Models\{RequestScraping, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RequestScrapingFactory extends Factory
{
    protected $model = RequestScraping::class;
    public function definition(): array
    {
        return [
            'query'      => $this->faker->word(),
            'created_at' => Carbon::now(),
            'user_id'    => User::factory(),
            'hash'       => $this->faker->uuid(),
            'status'     => RequestScrapingStatus::PENDING,
        ];
    }
}
