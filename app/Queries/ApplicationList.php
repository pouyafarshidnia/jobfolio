<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ApplicationList
{
    /**
     * @return LengthAwarePaginator<int, Application>
     */
    public function get(User $user, Request $request): LengthAwarePaginator
    {
        $perPage = $request->integer('per_page', 10);

        $date = is_array($request->date) ? sprintf(
            '%04d-%02d-%02d',
            $request->date['year'],
            $request->date['month'],
            $request->date['day']
        ) : null;

        return $user->applications()
            ->with('country')
            ->search($request->string('search')->value())
            ->status($request->string('status')->value())
            ->countryId($request->string('countryId')->value())
            ->date($date)
            ->latest('submitted_at')
            ->paginate($perPage)->withQueryString();
    }

    /**
     * @return array<int>
     */
    public function stats(User $user): array
    {
        return [
            'pending' => $user->applications()->whereStatus(ApplicationStatus::Pending)->count(),
            'rejected' => $user->applications()->whereStatus(ApplicationStatus::Rejected)->count(),
            'processing' => $user->applications()->whereStatus(ApplicationStatus::Processing)->count(),
            'today' => $user->applications()->whereBetween('submitted_at', [now()->format('Y-m-d 00:00:00'), now()->format('Y-m-d 23:59:59')])->count(),
        ];
    }

    /**
     * @return Collection<int, object{month: int, total: int}>
     */
    public function month(User $user, string $year): Collection
    {
        return collect($user->applications()
            ->selectRaw("CAST(strftime('%m', submitted_at) AS INTEGER) as month,COUNT(*) as total")
            ->whereRaw("strftime('%Y', submitted_at) = ?", [$year])
            ->groupByRaw("strftime('%m', submitted_at)")
            ->orderByRaw("strftime('%m', submitted_at)")
            ->get()
            ->toArray());
    }
}
