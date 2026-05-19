<!DOCTYPE html>
<html lang="en">

@php
$a = 21;
$b = 6
@endphp

<head>
    <meta charset="UTF-8">
    <title>Third</title>
    <style>
        .odd {color: red}
        .even {color:blue}
        .first {font-size: 2rem}
    </style>
</head>
<body>
<h1>Hello, Blade view!
    <br> 5 * 7 = {{5 * 7}}
    <br> PHP version: {{phpversion()}}
    <br> Остаток от деления: {{$a%$b}}
</h1>

<ol>
    @forelse($names as $name)
        <li @class(['odd' => $loop->odd, 'even' => $loop->even, 'first' => $loop->first])>{{ $name }}</li>
    @empty
        <li>None</li>
    @endforelse
</ol>
</body>
</html>
