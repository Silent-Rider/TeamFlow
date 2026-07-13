<?php

namespace App\Http\Requests\Project;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:32'],
            'description' => ['nullable', 'string', 'max:2000'],
            'members'     => ['nullable', 'array'],
            'members.*'   => ['integer', 'exists:users,id']
        ];
    }
}
