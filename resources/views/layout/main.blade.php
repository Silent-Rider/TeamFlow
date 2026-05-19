@extends('layout.root')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
@endsection

@section('body')
    <div class="container">
        <div class="col-3">@include('layout.sidebar')</div>
        <div class="col-9"></div>
        <br>
        <x-custom-input type="email" label="label1" placeholder="Write text here"></x-custom-input>
        <x-custom-input type="password" label="label2" placeholder="Write text here"></x-custom-input>
    </div>

@endsection
