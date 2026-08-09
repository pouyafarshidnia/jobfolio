<?php

declare(strict_types=1);

use App\Enums\CountryFlag;
use App\Models\Country;

test('model fields are correct', function (): void {

    $country = Country::factory()->create();

    $model = array_keys($country->toArray());
    $expected = ['id', 'name', 'flag'];

    sort($model);
    sort($expected);

    expect($model)->toBe($expected);
});

it('casts flag to enum', function (): void {
    $country = Country::factory()->create();

    expect($country->flag)->toBeInstanceOf(CountryFlag::class);
});
