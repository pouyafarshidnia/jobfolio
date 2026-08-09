<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationSalaryType;
use App\Enums\ApplicationStatus;
use App\Enums\ApplicationType;
use App\Exceptions\InvalidArgumentException;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
    use HasFactory;

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
     * Scope a query to search .
     *
     * @param  Builder<Application>  $query
     */
    #[Scope]
    protected function search(Builder $query, ?string $search): void
    {
        if ($search === null || $search === '') {
            return;
        }

        $query->where('company', 'like', sprintf('%%%s%%', $search));
    }

    /**
     * Scope a query to status .
     *
     * @param  Builder<Application>  $query
     */
    #[Scope]
    protected function status(Builder $query, mixed $status): void
    {

        $statusValue = match ($status) {
            'pending' => ApplicationStatus::Pending,
            'processing' => ApplicationStatus::Processing,
            'rejected' => ApplicationStatus::Rejected,
            'approved' => ApplicationStatus::Approved,
            default => null,
        };

        if ($statusValue === null) {
            return;
        }

        $query->where('status', $statusValue);
    }

    /**
     * Scope a query to date .
     *
     * @param  Builder<Application>  $query
     */
    #[Scope]
    protected function date(Builder $query, mixed $date): void
    {
        if ($date === null || $date === '') {
            return;
        }

        $start = $date.' 00:00:00';
        $end = $date.' 23:59:59';
        $query->whereBetween('submitted_at', [$start, $end]);
    }

    /**
     * Scope a query to country .
     *
     * @param  Builder<Application>  $query
     */
    #[Scope]
    protected function countryId(Builder $query, mixed $countryId): void
    {
        if ($countryId === null || $countryId === '') {
            return;
        }

        $query->where('country_id', $countryId);
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
            return $this->attributes['currency'].number_format((int) $this->attributes['salary']).' '.$this->salary_type->label();
        }

        return 'N/A';
    }
}
