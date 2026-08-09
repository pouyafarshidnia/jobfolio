<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CountryFlag;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
final class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
            'flag' => $this->faker->randomElement(CountryFlag::cases()),
        ];
    }

    public function usa(): static
    {
        return $this->state([
            'name' => 'United States',
            'flag' => 'us',
        ]);
    }
}
