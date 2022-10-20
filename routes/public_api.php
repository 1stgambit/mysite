<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;




Route::post('/register',[Controllers\AuthController::class, 'register']);
Route::post('/login', [Controllers\AuthController::class, 'login']);
Route::post('/logout', [Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');