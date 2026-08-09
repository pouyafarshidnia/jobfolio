<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationSalaryType: int
{
    case Hourly = 0;
    case Monthly = 1;
    case Yearly = 2;

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
            self::Hourly => 'Hourly',
            self::Monthly => 'Monthly',
            self::Yearly => 'Yearly',
        };
    }
}
