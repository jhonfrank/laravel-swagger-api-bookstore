<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;

Route::post('register', [UserController::class, 'register']);
Route::post('generate-token', [UserController::class, 'generateToken']);
Route::post('revoke-token', [UserController::class, 'revokeToken'])->middleware('auth:sanctum');
Route::post('revoke-all-tokens', [UserController::class, 'revokeAllTokens']);
Route::get('user-info', [UserController::class, 'userInfo'])->middleware('auth:sanctum');

Route::apiResource('books', BookController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');
Route::apiResource('orders.details', OrderDetailController::class)->middleware('auth:sanctum');
