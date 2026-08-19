<?php

namespace App\Actions\Applications;

use App\Enums\ApplicationSalaryType;
use App\Models\Application;

class UpdateApplication
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Application $application, array $attributes): bool
    {
        return $application->update([
            'country_id' => $attributes['country_id'],
            'company' => $attributes['company'],
            'position' => $attributes['position'],
            'submitted_at' => $attributes['submitted_at'] ?? now()->format('Y-m-d 00:00:00'),
            'salary' => $attributes['salary'],
            'currency' => $attributes['currency'] ?? null,
            'salary_type' => $attributes['salary_type'] ?? ApplicationSalaryType::Yearly->value,
            'type' => $attributes['type'],
            'link' => $attributes['link'],
        ]);
    }
}
