<?php

declare(strict_types=1);

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Models\Application;
use App\Models\Country;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can show the list of applications', function (): void {

    $user = User::factory()->create();
    Application::factory()->for($user, 'owner')->count(50)->create();
    Application::factory()->count(100)->create();

    $response = $this->actingAs($user)->get('applications')->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): Assert => $page->component('Applications/Index')
            ->has('list', 3)
            ->has('list.data', 10, fn (Assert $page): Assert => $page
                ->has('id')
                ->has('company')
                ->has('position')
                ->has('submittedAt', fn (Assert $page): Assert => $page
                    ->has('formatted')
                    ->has('value'))
                ->has('type', fn (Assert $page): Assert => $page
                    ->has('label')
                    ->has('value'))
                ->has('status')
                ->has('salary', fn (Assert $page): Assert => $page
                    ->has('display')
                    ->has('value'))
                ->has('currency')
                ->has('salaryType')
                ->has('link', fn (Assert $page): Assert => $page
                    ->has('icon')
                    ->has('url')
                    ->has('value'))
                ->has('country')
                ->has('actions', fn (Assert $page): Assert => $page
                    ->has('processable')
                    ->has('approvable')
                    ->has('rejectabale')))
            ->has('list.links')
            ->has('list.meta', fn (Assert $page): Assert => $page
                ->has('current_page')
                ->has('from')
                ->has('last_page')
                ->has('links')
                ->has('path')
                ->has('per_page')
                ->has('to')
                ->where('total', 50))
            ->has('countries', Country::count(), fn (Assert $page): Assert => $page
                ->has('name')
                ->has('id')
                ->has('flag', fn (Assert $page): Assert => $page
                    ->has('image')
                    ->has('thumbnail')
                    ->has('value')
                    ->has('label')))
    );
});

it('can create an application', function (): void {

    $user = User::factory()->create();
    $country = Country::factory()->create();

    expect(Application::count())->toBe(0);

    $this->actingAs($user)->post(route('applications.store'), [
        'country_id' => $country->id,
        'company' => 'Larvael',
        'position' => 'PHP Backend Developer',
        'submitted_at' => now(),
        'currency' => null,
        'salary' => null,
        'salary_type' => null,
        'type' => ApplicationType::Remote->value,
        'link' => 'https://example.com',
    ]);

    expect(Application::count())->toBe(1);
});

it('can update an application', function (): void {

    $user = User::factory()->create();
    $country = Country::factory()->create();
    $application = Application::factory()->for($user, 'owner')->create();

    $this->actingAs($user)->put(route('applications.update', $application), [
        'country_id' => $country->id,
        'company' => 'Laravel Update',
        'position' => 'Backend Developer PHP',
        'submitted_at' => $application->submitted_at,
        'salary' => '6000',
        'currency' => '$',
        'salary_type' => ApplicationSalaryType::Monthly->value,
        'type' => ApplicationType::Remote->value,
        'link' => 'gmail',
    ]);

    $updatedApplication = Application::find($application->id);
    expect(Application::count())->toBe(1)
        ->and($updatedApplication->company)->toBe('Laravel Update');
});

it('can change application status to process', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->for($user, 'owner')->pending()->create();

    $this->actingAs($user)->patch(route('applications.process', $application));

    $updatedApplication = Application::find($application->id);

    expect($updatedApplication->status)->toBe(ApplicationStatus::Processing);
});

it('can change application status to apprived', function (): void {

    $user = User::factory()->create();
    $application = Application::factory()->for($user, 'owner')->processing()->create();

    $this->actingAs($user)->patch(route('applications.approve', $application));

    $updatedApplication = Application::find($application->id);

    expect($updatedApplication->status)->toBe(ApplicationStatus::Approved);
});

it('can change application status to rejected', function (): void {

    $user = User::factory()->create();
    $processingApplication = Application::factory()->for($user, 'owner')->processing()->create();
    $pedningApplication = Application::factory()->for($user, 'owner')->pending()->create();

    $this->actingAs($user)->patch(route('applications.reject', $processingApplication));
    $this->actingAs($user)->patch(route('applications.reject', $pedningApplication));

    $processingUpdatedApplication = Application::find($processingApplication->id);
    $pendingUpdatedApplication = Application::find($pedningApplication->id);

    expect($processingUpdatedApplication->status)->toBe(ApplicationStatus::Rejected)
        ->and($pendingUpdatedApplication->status)->toBe(ApplicationStatus::Rejected);
});

/**
 *  Validation Tests
 */
describe('validation tests', function (): void {

    it('can validate country id', function (mixed $countryId): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['country_id' => $countryId]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['country_id' => $countryId]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['country_id']);
        $updateResponse->assertSessionHasErrors(['country_id']);
    })->with([null, '', 'usa', 0]);

    it('can validate company', function (mixed $company): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['company' => $company]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['company' => $company]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['company']);
        $updateResponse->assertSessionHasErrors(['company']);
    })->with([null, 123, str_repeat('a', 151)]);

    it('can validate position', function (mixed $position): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['position' => $position]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['position' => $position]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['position']);
        $updateResponse->assertSessionHasErrors(['position']);
    })->with([null, 123, str_repeat('a', 151)]);

    it('can validate submitted at', function (mixed $submittedAt): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['submitted_at' => $submittedAt]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['submitted_at' => $submittedAt]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['submitted_at']);
        $updateResponse->assertSessionHasErrors(['submitted_at']);
    })->with([123, '01 januray 2020', str_repeat('a', 256)]);

    it('can validate currency', function (mixed $currency): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['currency' => $currency]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['currency' => $currency]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['currency']);
        $updateResponse->assertSessionHasErrors(['currency']);
    })->with([123, 'dollar']);

    it('requires currency if salary has value', function (): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['currency' => null, 'salary' => 5000]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['currency' => null, 'salary' => 5000]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['currency']);
        $updateResponse->assertSessionHasErrors(['currency']);
    });

    it('can validate salary', function (mixed $salary): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['salary' => $salary]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['salary' => $salary]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['salary']);
        $updateResponse->assertSessionHasErrors(['salary']);
    })->with([5000, str_repeat('1', 256)]);

    it('requires salary if currency has value', function (): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['salary' => null, 'currency' => '$']);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['salary' => null, 'currency' => '$']);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['salary']);
        $updateResponse->assertSessionHasErrors(['salary']);
    });

    it('can validate salary type', function (mixed $salaryType): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['salary_type' => $salaryType]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['salary_type' => $salaryType]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['salary_type']);
        $updateResponse->assertSessionHasErrors(['salary_type']);
    })->with(['Yearly', 3]);

    it('can validate type', function (mixed $type): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['type' => $type]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['type' => $type]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['type']);
        $updateResponse->assertSessionHasErrors(['type']);
    })->with(['Remote', 3]);

    it('can validate link', function (mixed $link): void {

        $user = User::factory()->create();
        Country::factory()->create();
        $application = Application::factory()->for($user, 'owner')->create();

        expect(Application::count())->toBe(1);

        $createResponse = $this->actingAs($user)->post(route('applications.store'), ['link' => $link]);
        $updateResponse = $this->actingAs($user)->put(route('applications.update', $application), ['link' => $link]);

        expect(Application::count())->toBe(1);

        $createResponse->assertSessionHasErrors(['link']);
        $updateResponse->assertSessionHasErrors(['link']);
    })->with([null, 123, str_repeat('a', 256)]);
});
