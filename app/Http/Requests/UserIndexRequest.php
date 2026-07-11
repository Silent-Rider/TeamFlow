<?php

namespace App\Http\Requests;

class UserIndexRequest extends PaginatorFormRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'project_id' => ['nullable', 'exists:projects,id']
        ]);
    }

    public function getPerPage(): int {
        return $this->validated('per_page', 50);
    }

    public function getProjectId(): ?int
    {
        return $this->validated("project_id");
    }
}
