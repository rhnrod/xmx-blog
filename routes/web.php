<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'posts']);
Route::get('/post/{id}', [PostController::class, 'showPost'])->name('post.show');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');