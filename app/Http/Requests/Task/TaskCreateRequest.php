<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'priority'    => ['required', Rule::enum(TaskPriority::class)],
            'description' => ['nullable', 'string', 'max:2000'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
            'project_id'  => ['nullable', 'integer', 'exists:projects,id'],
        ];
    }

    public function getTaskData(): array
    {
        return array_merge($this->validated(), [
            'creator_id' => auth()->id(),
        ]);
    }

    public function getProjectId(): ?int
    {
        return $this->validated("project_id");
    }

    public function getAssigneeId(): int
    {
        return $this->validated("assignee_id");
    }
}
