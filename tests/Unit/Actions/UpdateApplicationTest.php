<?php

declare(strict_types=1);

use App\Actions\Applications\UpdateApplication;
use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Models\Application;
use App\Models\User;

it('may create an application', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->pending()->create(['user_id' => $user->id]);

    resolve(UpdateApplication::class)->handle(
        $application,
        [
            'country_id' => $application->country_id,
            'company' => 'Laravel Update',
            'position' => 'Backend Developer PHP',
            'submitted_at' => $application->submitted_at,
            'salary' => 6000,
            'currency' => '$',
            'salary_type' => ApplicationSalaryType::Monthly->value,
            'status' => ApplicationStatus::Approved,
            'type' => ApplicationType::Remote->value,
            'link' => 'gmail',
        ]
    );

    $updatedApplication = Application::whereUserId($user->id)->first();

    expect($updatedApplication)
        ->toBeInstanceOf(Application::class)
        ->and($updatedApplication->country_id)->toBe($application->country_id)
        ->and($updatedApplication->company)->toBe('Laravel Update')
        ->and($updatedApplication->position)->toBe('Backend Developer PHP')
        ->and($updatedApplication->submitted_at->format('Y-m-d'))->toBe($application->submitted_at->format('Y-m-d'))
        ->and($updatedApplication->salary)->toBe('6000')
        ->and($updatedApplication->currency)->toBe('$')
        ->and($updatedApplication->salary_type)->toBe(ApplicationSalaryType::Monthly)
        ->and($updatedApplication->status)->toBe(ApplicationStatus::Pending)
        ->and($updatedApplication->type)->toBe(ApplicationType::Remote)
        ->and($updatedApplication->link)->toBe('gmail');
});
