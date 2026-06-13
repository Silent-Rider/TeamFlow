<div
    {{ $attributes->merge([
        'class' => "flex items-center justify-center rounded-full text-white font-bold select-none",
        'style' => "background-color: {$backgroundColor};"]) }}
    aria-label="Avatar for {{ $name }}">
    {{ $initials }}
</div>
