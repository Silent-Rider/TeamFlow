<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\PaginatorFormRequest;

class TaskIndexRequest extends PaginatorFormRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'project_id' => ['nullable', 'exists:projects,id'],
            'filter' => ['nullable', 'string', 'in:all,active,done'],
        ]);
    }

    public function getProjectId(): ?int
    {
        return $this->validated("project_id");
    }

    public function getFilter(): string {
        return $this->validated('filter', 'all');
    }
}
