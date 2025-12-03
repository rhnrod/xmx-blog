<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'posts']);
Route::get('/posts/{id}', [PostController::class, 'showPost'])->name('posts.show');
