<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskReportController;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/all', [UserController::class, 'getAll'])->name('users.getAll');
    Route::post('/user', [UserController::class, 'store'])->name('users.store');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/all', [ProjectController::class, 'getAll'])->name('projects.getAll');

    Route::post('tasks', [TaskController::class, 'store'])->name('task.store');
    Route::get('tasks', [TaskController::class, 'getAllEvents'])->name('task.getAll');
    Route::get('tasks/user/{id}', [TaskController::class, 'getTasksByUser'])->name('task.getTasksByUser');

    Route::post('/generate-report-pdf', [TaskReportController::class, 'generateReport'])->name('generate.report.pdf');
});

require __DIR__.'/auth.php';
