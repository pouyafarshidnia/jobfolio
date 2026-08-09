<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\CountryFlag;
use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

final class CountryList
{
    /**
     * @return array{list: Collection<int, Country>, flags: SupportCollection<int, array{value: string, label: string, thumbnail: string}>}
     */
    public function get(): array
    {
        $countries = Country::all();
        $flags = collect(CountryFlag::cases())->map(fn (CountryFlag $flag): array => [
            'value' => $flag->value,
            'label' => $flag->label(),
            'thumbnail' => $flag->thumbnail(),
        ])->values();

        return [
            'list' => $countries,
            'flags' => $flags,
        ];
    }
}
