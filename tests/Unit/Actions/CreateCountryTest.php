<?php

declare(strict_types=1);

use App\Actions\Countries\CreateCountry;
use App\Enums\CountryFlag;
use App\Models\Country;

it('may create a country', function (): void {

    $country = resolve(CreateCountry::class)->handle('us');

    expect($country)
        ->toBeInstanceOf(Country::class)
        ->and($country->name)->toBe('United States')
        ->and($country->flag)->toBe(CountryFlag::UnitedStates);
});
