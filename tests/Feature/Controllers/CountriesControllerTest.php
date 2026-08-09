<?php

declare(strict_types=1);

use App\Enums\CountryFlag;
use App\Models\Country;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can show the list of countries', function (): void {

    $user = User::factory()->create();
    Country::factory()->count(50)->create();

    $response = $this->actingAs($user)->get('countries')->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): Assert => $page->component('Countries/Index')
            ->has('list', 50, fn (Assert $page): Assert => $page
                ->has('id')
                ->has('name')
                ->has('flag'))
            ->has('flags', count(CountryFlag::cases()), fn (Assert $page): Assert => $page
                ->has('value')
                ->has('label')
                ->has('thumbnail'))
    );
});

it('can create a country', function (): void {

    $user = User::factory()->create();

    expect(Country::count())->toBe(0);

    $this->actingAs($user)->post(route('countries.store'), [
        'country' => CountryFlag::UnitedStates->value,
    ]);

    expect(Country::count())->toBe(1);
});

/**
 * Rules: required,unique,exists in country flag values
 */
it('can validate country name', function (mixed $countryName): void {

    $user = User::factory()->create();
    Country::factory()->usa()->create();

    expect(Country::count())->toBe(1);

    $response = $this->actingAs($user)->post(route('countries.store'), [
        'country' => $countryName,
    ]);

    expect(Country::count())->toBe(1);

    $response->assertSessionHasErrors(['country']);
})->with([null, '', 'Argentina', 'us']);
