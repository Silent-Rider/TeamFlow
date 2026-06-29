<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginatorFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ];
    }

    public function getPage(): int {
        return $this->validated('page', 1);
    }

    public function getPerPage(): int {
        return $this->validated('per_page', 20);
    }

    public function getPaginator(Collection $models, int $total): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $models,
            $total,
            $this->getPerPage(),
            $this->getPage(),
            ['path' => $this->url(), 'query' => $this->query()]
        );
    }
}
