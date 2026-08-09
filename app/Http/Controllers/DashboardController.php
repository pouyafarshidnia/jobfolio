<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Queries\ApplicationList;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request, #[CurrentUser] User $user, ApplicationList $list): Response
    {
        $year = $request->string('year', now()->format('Y'));

        return Inertia::render('Dashboard', [
            'stats' => $list->stats($user),
            'month' => $list->month($user, $year),
            'year' => (string) $year,
        ]);
    }
}
