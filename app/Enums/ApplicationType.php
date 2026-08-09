<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationType: int
{
    case Remote = 0;
    case Hybrid = 1;
    case Onsite = 2;

    /**
     * @return array{value: int, label: string}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Remote => 'Remote',
            self::Hybrid => 'Hybrid',
            self::Onsite => 'Onsite',
        };
    }
}
