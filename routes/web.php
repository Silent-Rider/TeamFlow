<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/tasks');
    }
    $cacheKey = 'welcome_page:' . app()->currentLocale();
    return Cache::remember($cacheKey, now()->addHours(24), function () {
        return view('welcome')->render();
    });
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
        Route::post('/', 'create')->name('tasks.create');
        Route::put('/{task}', 'update')->name('tasks.update');
        Route::delete('/{task}', 'destroy')->name('tasks.destroy');
        Route::patch('/{task}/toggle', 'toggle')->name('tasks.toggle');
        Route::get('/{task}', 'show')->name('tasks.show');
    });

    Route::controller(ProjectController::class)->prefix('projects')->group(function () {
        Route::get('/', 'index')->name('projects');
        Route::post('/', 'create')->name('projects.create');
        Route::put('/{project}', 'update')->name('projects.update');
        Route::delete('/{project}', 'destroy')->name('projects.destroy');

        Route::prefix('{project}/members')->group(function () {
            Route::post('/', 'addMember')->name('projects.members.add');
            Route::delete('/{user}', 'removeMember')->name('projects.members.remove');
            Route::patch('/{user}', 'updateMemberRole')->name('projects.members.update');
        });
    });

    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', fn() => response()->json(['status' => 'ok']))->name('admin.index');
    });
});

require __DIR__.'/auth.php';
