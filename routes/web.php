<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'posts']);
Route::get('/post/{id}', [PostController::class, 'showPost'])->name('post.show');
