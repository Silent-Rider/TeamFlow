<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AvatarPlaceholder extends Component
{
    public string $name;
    public string $initials;
    public string $backgroundColor;

    public function __construct(string $name = '')
    {
        $this->name = trim($name);

        $this->initials = collect(explode(' ', $this->name))
            ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->take(2)
            ->implode('') ?: '?';

        $colors = [
            '#EF4444', '#F97316', '#F59E0B', '#10B981',
            '#059669', '#14B8A6', '#06B6D4', '#3B82F6',
            '#6366F1', '#8B5CF6', '#A855F7', '#D946EF',
            '#EC4899', '#F43F5E'
        ];

        $hash = abs(crc32($this->name));
        $this->backgroundColor = $colors[$hash % count($colors)];
    }

    public function render(): View|Closure|string
    {
        return view('components.avatar-placeholder');
    }
}
