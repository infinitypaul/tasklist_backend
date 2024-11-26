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
    Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);
});

