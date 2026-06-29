<?php

namespace App\Http\Requests;

class UserIndexRequest extends PaginatorFormRequest
{
    public function getPerPage(): int {
        return $this->validated('per_page', 50);
    }
}
