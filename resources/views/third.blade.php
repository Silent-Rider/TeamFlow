<!DOCTYPE html>
<html lang="en">

@php
$a = 21;
$b = 6
@endphp

<head>
    <meta charset="UTF-8">
    <title>Third</title>
</head>
<body>
<h1>Hello, Blade view!
    <br> 5 * 7 = {{5 * 7}}
    <br> PHP version: {{phpversion()}}
    <br> Остаток от деления: {{$a%$b}}
</h1>
</body>
</html>
