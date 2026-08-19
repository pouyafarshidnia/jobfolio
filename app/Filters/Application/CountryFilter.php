<?php

namespace App\Filters\Application;

use EleFilter\Database\ModelFilter;

class CountryFilter extends ModelFilter
{
    protected string $column = 'country_id';

    public function apply(mixed $param): void
    {

        if ($param === null || $param === '') {
            return;
        }

        $this->equal($param);
    }
}
