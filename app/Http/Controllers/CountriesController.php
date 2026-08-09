<?php

namespace App\Http\Controllers;

use App\Actions\Countries\CreateCountry;
use App\Http\Requests\Countries\CreateCountryRequest;
use App\Queries\CountryList;
use Inertia\Inertia;
use Inertia\Response;

class CountriesController
{
    public function index(CountryList $list): Response
    {
        return Inertia::render('Countries/Index', $list->get());
    }

    public function store(CreateCountryRequest $request, CreateCountry $action): void
    {
        $action->handle($request->string('country'));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Country Added.')]);
    }
}
