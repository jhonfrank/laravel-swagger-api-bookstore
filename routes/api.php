<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;

Route::apiResource('books', BookController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('orders.details', OrderDetailController::class);
