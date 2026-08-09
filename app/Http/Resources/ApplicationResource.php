<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'position' => $this->position,
            'submittedAt' => ['formatted' => $this->submitted_at->format('j F, Y'), 'value' => $this->submitted_at->format('Y-m-d')],
            'type' => ['label' => $this->type->label(), 'value' => $this->type->value],
            'status' => $this->status->toArray(),
            'salary' => ['display' => $this->salary_display, 'value' => $this->salary],
            'currency' => $this->currency,
            'salaryType' => $this->salary_type->value,
            'link' => ['icon' => $this->link_icon, 'url' => $this->link_url, 'value' => $this->link],
            'country' => $this->country,
            'actions' => [
                'processable' => $this->status->pending(),
                'approvable' => $this->status->processing(),
                'rejectabale' => $this->status->pending() || $this->status->processing(),
            ],
        ];
    }
}
