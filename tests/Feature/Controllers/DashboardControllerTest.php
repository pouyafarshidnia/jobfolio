<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('can show the dashboard', function (): void {

    $user = User::factory()->create();
    Application::factory()->for($user, 'owner')->pending()->count(18)->create();
    Application::factory()->for($user, 'owner')->processing()->count(3)->create();
    Application::factory()->for($user, 'owner')->rejected()->count(15)->create();
    Application::factory()->for($user, 'owner')->pending()->count(2)->create(['submitted_at' => now()]);

    $response = $this->actingAs($user)->get(route('dashboard'))->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page): Assert => $page->component('Dashboard')
            ->has('stats', fn (Assert $page): Assert => $page
                ->where('pending', 20)
                ->where('rejected', 15)
                ->where('processing', 3)
                ->where('today', 2))
            ->has('month')
            ->where('year', now()->format('Y'))
    );
});
