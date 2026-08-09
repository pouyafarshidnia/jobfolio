<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\User;

it('does not let non owner to update the applciation', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->create();

    $response = $this->actingAs($user)->put(route('applications.update', $application), [
        'country_id' => $application->country_id,
        'company' => 'Laravel Update',
        'position' => 'Backend Developer PHP',
        'submitted_at' => $application->submitted_at,
        'salary' => '6000',
        'currency' => '$',
        'salary_type' => $application->salary_type->value,
        'type' => $application->type->value,
        'link' => 'gmail',
    ]);

    $response->assertStatus(404);
});

it('does not let non owner to change application status to processing', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->pending()->create();

    $response = $this->actingAs($user)->patch(route('applications.process', $application));

    $response->assertStatus(404);
});

it('does not let non owner to change application status to rejected', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->pending()->create();

    $response = $this->actingAs($user)->patch(route('applications.reject', $application));

    $response->assertStatus(404);
});

it('does not let non owner to change application status to approve', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->processing()->create();

    $response = $this->actingAs($user)->patch(route('applications.approve', $application));

    $response->assertStatus(404);
});
