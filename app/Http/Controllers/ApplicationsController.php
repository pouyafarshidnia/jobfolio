<?php

namespace App\Http\Controllers;

use App\Actions\Applications\CreateApplication;
use App\Actions\Applications\UpdateApplication;
use App\Http\Requests\Applications\CreateApplicationRequest;
use App\Http\Requests\Applications\UpdateApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Models\Application;
use App\Models\Country;
use App\Models\User;
use App\Queries\ApplicationList;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationsController extends Controller
{
    public function index(Request $request, #[CurrentUser] User $user, ApplicationList $list): Response
    {
        $list = new ApplicationCollection($list->get($user, $request));
        $countries = Country::all();

        return Inertia::render('Applications/Index', compact('list', 'countries'));
    }

    public function store(CreateApplicationRequest $request, #[CurrentUser] User $user, CreateApplication $action): void
    {
        $action->handle($user, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Appication Added.')]);
    }

    public function update(UpdateApplicationRequest $request, Application $application, UpdateApplication $action): void
    {
        $action->handle($application, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Appication Updated.')]);
    }

    public function process(Application $application): void
    {
        $application->process();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Appication status set to processing.')]);
    }

    public function approve(Application $application): void
    {
        $application->approve();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Appication status set to approved.')]);
    }

    public function reject(Application $application): void
    {
        $application->reject();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Appication status set to rejected.')]);
    }
}
