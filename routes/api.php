<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('tasks/shared', [\App\Http\Controllers\ShareTaskController::class, 'shared_with_me']);
    Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);

    Route::post('tasks/mark/{task}', [\App\Http\Controllers\TaskController::class, 'mark_task']);
    Route::post('tasks/share/{task}', [\App\Http\Controllers\ShareTaskController::class, 'share_task']);
    Route::get('permissions', [\App\Http\Controllers\ShareTaskController::class, 'permission']);

});

