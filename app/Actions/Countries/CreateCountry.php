<?php

declare(strict_types=1);

namespace App\Actions\Countries;

use App\Enums\CountryFlag;
use App\Models\Country;

final readonly class CreateCountry
{
    public function handle(string $flag): Country
    {
        $countryFlag = CountryFlag::from($flag);

        return Country::query()->create([
            'name' => $countryFlag->label(),
            'flag' => $flag,
        ]);
    }
}
