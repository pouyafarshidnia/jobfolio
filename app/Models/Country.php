<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CountryFlag;
use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CountryFlag $flag
 */
final class Country extends Model
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'flag' => CountryFlag::class,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'flag' => [
                'value' => $this->flag->value,
                'label' => $this->flag->label(),
                'thumbnail' => $this->flag->thumbnail(),
                'image' => $this->flag->image(),
            ],
        ];
    }
}
