<?php

declare(strict_types=1);

namespace App\Http\Requests\Applications;

use App\Models\Application;
use Illuminate\Container\Attributes\RouteParameter;

final class UpdateApplicationRequest extends CreateApplicationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(#[RouteParameter('application')] Application $application): bool
    {
        return $application->user_id === $this->user()?->id;
    }
}
