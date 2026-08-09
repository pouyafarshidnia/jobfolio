<?php

declare(strict_types=1);

namespace App\Http\Requests\Applications;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'company' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'submitted_at' => ['nullable', 'date', 'max:255'],
            'currency' => ['required_with:salary', 'nullable', 'string', 'in:$,€'],
            'salary' => ['required_with:currency', 'nullable', 'string', 'max:255'],
            'salary_type' => ['nullable', 'in:0,1,2'],
            'type' => ['required', 'in:0,1,2'],
            'link' => ['required', 'string', 'max:255'],
        ];
    }
}
