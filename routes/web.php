<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskApplicationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
});

Route::middleware(['auth', 'nastavnik'])->group(function () {
    Route::resource('tasks', TaskController::class)->except(['index', 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    // Task application routes (for students)
    Route::post('/tasks/{task}/apply', [TaskApplicationController::class, 'apply'])->name('tasks.apply');
    Route::delete('/tasks/{task}/withdraw', [TaskApplicationController::class, 'withdraw'])->name('tasks.withdraw');

    // Application management routes (for professors)
    Route::get('/tasks/{task}/applications', [TaskApplicationController::class, 'showApplications'])->name('tasks.applications');
    Route::patch('/tasks/{task}/applications/{application}/status', [TaskApplicationController::class, 'updateStatus'])->name('tasks.applications.updateStatus');
});

// Language switcher route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

require __DIR__.'/auth.php';
