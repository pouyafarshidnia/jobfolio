<?php

declare(strict_types=1);

use App\Actions\Applications\CreateApplication;
use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Models\Application;
use App\Models\Country;
use App\Models\User;

it('may create an application', function (): void {

    $user = User::factory()->create();
    $country = Country::factory()->create();

    resolve(CreateApplication::class)->handle(
        $user,
        [
            'country_id' => $country->id,
            'company' => 'Laravel',
            'position' => 'Backend Developer',
            'submitted_at' => null,
            'salary' => 5000,
            'currency' => '$',
            'salary_type' => ApplicationSalaryType::Monthly->value,
            'status' => null,
            'type' => ApplicationType::Remote->value,
            'link' => 'linkedin',
        ]
    );

    $application = Application::first();

    expect($application)
        ->toBeInstanceOf(Application::class)
        ->and($application->country_id)->toBe($country->id)
        ->and($application->company)->toBe('Laravel')
        ->and($application->position)->toBe('Backend Developer')
        ->and($application->submitted_at->format('Y-m-d'))->toBe(now()->format('Y-m-d'))
        ->and($application->salary)->toBe('5000')
        ->and($application->currency)->toBe('$')
        ->and($application->salary_type)->toBe(ApplicationSalaryType::Monthly)
        ->and($application->status)->toBe(ApplicationStatus::Pending)
        ->and($application->type)->toBe(ApplicationType::Remote)
        ->and($application->link)->toBe('linkedin');
});
