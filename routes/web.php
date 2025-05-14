<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BeliController;

Route::post('register', [UserController::class, 'register']);
Route::get('users', [UserController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::post('belis', [BeliController::class, 'store']);
Route::get('belis', [BeliController::class, 'index']);
