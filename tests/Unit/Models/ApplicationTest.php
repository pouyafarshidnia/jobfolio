<?php

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Models\Application;
use App\Models\Country;
use App\Models\User;
use Carbon\CarbonImmutable;

test('model fields are correct', function (): void {

    $application = Application::factory()->create();

    $model = array_keys($application->toArray());
    $expected = [
        'id',
        'user_id',
        'country_id',
        'company',
        'position',
        'submitted_at',
        'status',
        'salary',
        'currency',
        'salary_type',
        'type',
        'link',
        'created_at',
        'updated_at',
    ];

    sort($model);
    sort($expected);

    expect($model)->toBe($expected);
});

/**
 * Casts
 */
describe('cast tests', function (): void {

    it('casts status to enum', function (): void {

        $application = Application::factory()->create();

        expect($application->status)->ToBeInstanceOf(ApplicationStatus::class);
    });

    it('casts salary type to enum', function (): void {

        $application = Application::factory()->create();

        expect($application->salary_type)->ToBeInstanceOf(ApplicationSalaryType::class);
    });

    it('casts type to enum', function (): void {

        $application = Application::factory()->create();

        expect($application->type)->ToBeInstanceOf(ApplicationType::class);
    });

    it('casts submitted at to carbon', function (): void {

        $application = Application::factory()->create();

        expect($application->submitted_at)->ToBeInstanceOf(CarbonImmutable::class);
    });

    it('casts created at to carbon', function (): void {

        $application = Application::factory()->create();

        expect($application->created_at)->ToBeInstanceOf(CarbonImmutable::class);
    });

    it('casts updted at to carbon', function (): void {

        $application = Application::factory()->create();

        expect($application->updated_at)->ToBeInstanceOf(CarbonImmutable::class);
    });
});

/**
 * Relations
 */
describe('relation tests', function (): void {

    it('belongs to an owner', function (): void {

        $application = Application::factory()->create();

        expect($application->owner)->toBeInstanceOf(User::class);
    });

    it('belongs to a country', function (): void {

        $application = Application::factory()->create();

        expect($application->country)->toBeInstanceOf(Country::class);
    });
});

/**
 * Attributes
 */
describe('attribute tests', function (): void {

    it('has link icon attribute', function (): void {

        $linkedin = Application::factory()->create(['link' => 'linkedin easy apply']);
        $gmail = Application::factory()->create(['link' => 'gmail']);
        $externalUrl = Application::factory()->create(['link' => 'https://example.com']);

        expect($linkedin->link_icon)->toBe('Link')
            ->and($gmail->link_icon)->toBe('Mail')
            ->and($externalUrl->link_icon)->toBe('ExternalLink');
    });

    it('has link url attribute', function (): void {

        $linkedin = Application::factory()->create(['link' => 'linkedin easy apply']);
        $gmail = Application::factory()->create(['link' => 'gmail']);
        $externalUrl = Application::factory()->create(['link' => 'https://example.com']);

        expect($linkedin->link_url)->toBe('')
            ->and($gmail->link_url)->toBe('')
            ->and($externalUrl->link_url)->toBe('https://example.com');
    });

    it('has salary display attribute', function (): void {

        $withSalary = Application::factory()->create(['salary' => 60000, 'currency' => '$', 'salary_type' => ApplicationSalaryType::Yearly->value]);
        $salaryLess = Application::factory()->create(['salary' => null, 'currency' => null]);

        expect($withSalary->salary_display)->toBe('$60,000 Yearly')
            ->and($salaryLess->salary_display)->toBe('N/A');
    });
});
