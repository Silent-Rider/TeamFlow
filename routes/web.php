<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {

});

$names = ['Anna', 'Vladimir', 'Kirill'];

Route::view('/first', 'first');
Route::view('/second', 'second', ['name' => request('name')]);
Route::view('/third', 'third', ['names' => $names]);


