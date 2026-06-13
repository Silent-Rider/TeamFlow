<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'project_id' => ['nullable', 'exists:projects,id'],
            'filter' => ['nullable', 'string', 'in:all,active,done'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ];
    }

    public function getProjectId(): ?int
    {
        return $this->validated("project_id");
    }

    public function getFilter(): string {
        return $this->validated('filter', 'all');
    }

    public function getPerPage(): int {
        return $this->validated('per_page', 20);
    }
}
