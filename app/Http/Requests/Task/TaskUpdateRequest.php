<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'assignee_id' => ['sometimes', 'integer', 'exists:users,id'],
            'priority'    => ['sometimes', Rule::enum(TaskPriority::class)],
            'description' => ['nullable', 'string', 'max:2000'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
            'project_id'  => ['nullable', 'integer', 'exists:projects,id'],
        ];
    }
}
