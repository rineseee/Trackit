<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'name')->ignore($project?->id),
            ],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
