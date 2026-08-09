<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationStatus: int
{
    case Pending = 0;
    case Processing = 1;
    case Rejected = -1;
    case Approved = 2;

    /**
     * @return array{label: string, color: string}
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label(),
            'color' => $this->color(),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Rejected => 'Rejected',
            self::Approved => 'Approved',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Processing => 'info',
            self::Rejected => 'danger',
            self::Approved => 'success',
        };
    }

    public function pending(): bool
    {
        return $this === self::Pending;
    }

    public function processing(): bool
    {
        return $this === self::Processing;
    }

    public function rejected(): bool
    {
        return $this === self::Rejected;
    }

    public function approved(): bool
    {
        return $this === self::Approved;
    }
}
