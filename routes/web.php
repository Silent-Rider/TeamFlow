<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/tasks');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'edit')->name('profile.edit');
        Route::patch('/', 'updateInfo')->name('profile.info.update');
        Route::put('/', 'updateAvatar')->name('profile.avatar.update');
        Route::delete('/', 'destroy')->name('profile.destroy');
    });

    Route::controller(TaskController::class)->prefix('tasks')->group(function () {
        Route::get('/', 'index')->name('tasks');
        Route::patch('/{task}/toggle', 'toggle')->name('tasks.toggle');
    });

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');

    Route::get('/users', [UserController::class, 'index'])->name('users');
});

require __DIR__.'/auth.php';
