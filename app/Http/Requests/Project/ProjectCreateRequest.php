<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function getProjectData(): array
    {
        return array_merge($this->validated(), [
            'creator_id' => auth()->id(),
        ]);
    }
}
