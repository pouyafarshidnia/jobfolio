<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Models\Application;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
final class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'country_id' => Country::factory(),
            'company' => $this->faker->company(),
            'position' => $this->faker->randomElement(['Bakcend Developer', 'Fullstack Developer', 'Software Developer']),
            'submitted_at' => $this->faker->date(),
            'salary' => $this->faker->numberBetween(2500, 120000),
            'currency' => $this->faker->randomElement(['$', '€']),
            'salary_type' => $this->faker->randomElement(ApplicationSalaryType::cases()),
            'status' => $this->faker->randomElement(ApplicationStatus::cases()),
            'type' => $this->faker->randomElement(ApplicationType::cases()),
            'link' => $this->faker->url(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => ApplicationStatus::Pending]);
    }

    public function processing(): static
    {
        return $this->state(['status' => ApplicationStatus::Processing]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => ApplicationStatus::Rejected]);
    }

    public function approved(): static
    {
        return $this->state(['status' => ApplicationStatus::Approved]);
    }
}
