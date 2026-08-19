<?php

namespace App\Filters\Application;

use App\Enums\ApplicationStatus;
use EleFilter\Database\ModelFilter;

class StatusFilter extends ModelFilter
{
    protected string $column = 'status';

    public function pending(): void
    {
        $this->equal(ApplicationStatus::Pending);
    }

    public function processing(): void
    {
        $this->equal(ApplicationStatus::Processing);
    }

    public function rejected(): void
    {
        $this->equal(ApplicationStatus::Rejected);
    }

    public function approved(): void
    {
        $this->equal(ApplicationStatus::Approved);
    }
}
