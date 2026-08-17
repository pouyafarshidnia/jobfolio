<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Exceptions\InvalidArgumentException;
use Database\Factories\ApplicationFactory;
use EleFilter\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property ApplicationStatus $status
 * @property ApplicationSalaryType $salary_type
 * @property ApplicationType $type
 * @property Carbon $submitted_at
 * @property string $link
 * @property string $company
 * @property string $position
 * @property int $country_id
 * @property ?string $currency
 * @property ?string $salary
 */
final class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory, Filterable;

    protected $perPage = 10;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'salary_type' => ApplicationSalaryType::class,
            'type' => ApplicationType::class,
            'submitted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function process(): void
    {
        throw_if($this->status !== ApplicationStatus::Pending, InvalidArgumentException::class);

        $this->status = ApplicationStatus::Processing;
        $this->save();
    }

    public function approve(): void
    {
        throw_if($this->status !== ApplicationStatus::Processing, InvalidArgumentException::class);

        $this->status = ApplicationStatus::Approved;
        $this->save();
    }

    public function reject(): void
    {
        throw_unless(in_array($this->status, [ApplicationStatus::Pending, ApplicationStatus::Processing]), InvalidArgumentException::class);

        $this->status = ApplicationStatus::Rejected;
        $this->save();
    }


    /**
     * Attributes
     */
    protected function getLinkIconAttribute(): string
    {
        if (Str::of($this->attributes['link'])->startsWith('linkedin')) {
            return 'Link';
        }

        if (Str::of($this->attributes['link'])->startsWith('gmail')) {
            return 'Mail';
        }

        return 'ExternalLink';
    }

    protected function getLinkUrlAttribute(): string
    {
        if (Str::of($this->attributes['link'])->startsWith('linkedin') or Str::of($this->attributes['link'])->startsWith('gmail')) {
            return '';
        }

        return $this->attributes['link'];
    }

    protected function getSalaryDisplayAttribute(): string
    {
        if ($this->attributes['salary'] !== null) {
            return $this->attributes['currency'] . number_format((int) $this->attributes['salary']) . ' ' . $this->salary_type->label();
        }

        return 'N/A';
    }
}
