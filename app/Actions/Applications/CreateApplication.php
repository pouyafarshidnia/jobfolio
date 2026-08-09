<?php

declare(strict_types=1);

namespace App\Actions\Applications;

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Models\User;

final readonly class CreateApplication
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(User $user, array $attributes): void
    {
        $user->applications()->create([
            'country_id' => $attributes['country_id'],
            'company' => $attributes['company'],
            'position' => $attributes['position'],
            'submitted_at' => $attributes['submitted_at'] ?? now()->format('Y-m-d 00:00:00'),
            'salary' => $attributes['salary'],
            'currency' => $attributes['currency'] ?? null,
            'salary_type' => $attributes['salary_type'] ?? ApplicationSalaryType::Yearly->value,
            'status' => ApplicationStatus::Pending->value,
            'type' => $attributes['type'],
            'link' => $attributes['link'],
        ]);
    }
}
