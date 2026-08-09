<?php

declare(strict_types=1);

use App\Models\Application;

/**
 * Dashboard
 */
describe('countries', function (): void {
    it('does not allow guests to see dashboard', function (): void {

        $this->get(route('dashboard'))->assertStatus(302);
    });
});

/**
 * Countries
 */
describe('countries', function (): void {
    it('does not allow guests to see countries', function (): void {

        $this->get(route('countries'))->assertStatus(302);
    });

    it('does not allow guests to store country', function (): void {

        $this->post(route('countries'))->assertStatus(302);
    });
});

/**
 * Applicationbs
 */
describe('countries', function (): void {
    it('does not allow guests to see applications', function (): void {

        $this->get(route('applications'))->assertStatus(302);
    });

    it('does not allow guests to store application', function (): void {

        $this->post(route('applications'))->assertStatus(302);
    });

    it('does not allow guests to update application', function (): void {

        $application = Application::factory()->create();
        $this->put(route('applications.update', $application))->assertStatus(302);
    });

    it('does not allow guests to process application', function (): void {

        $application = Application::factory()->pending()->create();
        $this->patch(route('applications.process', $application))->assertStatus(302);
    });

    it('does not allow guests to approve application', function (): void {

        $application = Application::factory()->processing()->create();
        $this->patch(route('applications.approve', $application))->assertStatus(302);
    });

    it('does not allow guests to reject application', function (): void {

        $application = Application::factory()->pending()->create();
        $this->patch(route('applications.reject', $application))->assertStatus(302);
    });
});
