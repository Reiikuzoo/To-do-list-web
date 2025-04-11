<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
Route::post('/tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
Route::get('/tasks/prioritas', [TaskController::class, 'prioritas'])->name('tasks.prioritas');
Route::post('/tasks/update-order', [TaskController::class, 'updateOrder'])->name('tasks.updateOrder');


Route::post('/subtasks', [SubTaskController::class, 'store'])->name('subtasks.store');
Route::put('/subtasks/{subtask}', [SubTaskController::class, 'update'])->name('subtasks.update');
Route::delete('/subtasks/{subtask}', [SubTaskController::class, 'destroy'])->name('subtasks.destroy');
Route::post('/subtasks/{subTask}/complete', [SubTaskController::class, 'complete'])->name('subtasks.complete');
Route::post('/subtasks/{subTask}/complete', [SubTaskController::class, 'markComplete'])->name('subtasks.complete');
Route::post('/subtasks/update-order', [SubTaskController::class, 'updateOrder'])->name('subtasks.updateOrder');


Route::get('/kalender', [TaskController::class, 'kalender'])->name('tasks.kalender');
Route::get('/kalender/events', [TaskController::class, 'kalenderEvents'])->name('tasks.kalender.events');


