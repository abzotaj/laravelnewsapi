<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/user/register', [ AuthController::class, 'createUser' ]);
Route::post('/user/login', [ AuthController::class, 'loginUser' ]);
Route::get('/user', [ UserController::class, 'get' ])->middleware('auth:sanctum');
Route::get('/user/logout', [ UserController::class, 'logout' ])->middleware('auth:sanctum');
Route::put('/user', [ UserController::class, 'update' ])->middleware('auth:sanctum');
Route::get('/user/news', [ NewsController::class, 'getNewsFeed' ])->middleware('auth:sanctum');

