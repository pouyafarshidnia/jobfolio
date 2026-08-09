<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnsureIsApplicationOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $application = $request->route('application');

        abort_unless($application instanceof Application && $request->user()?->is($application->owner), 404);

        return $next($request);
    }
}
