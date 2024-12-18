<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/indicator', [\App\Http\Controllers\IndicatorController::class, 'index']);

    Route::post('/indicator/{{id}}', [AuthController::class, 'logout']);
});
