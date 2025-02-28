<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;





Auth::routes();

Route::group(['middleware' => 'auth'], function (){
    Route::get('/', [PostController::class, 'index'])->name('index');

    Route::group(['prefix' => 'post', 'as'=>'post.'], function(){
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/show/', [PostController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update',[PostController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy',[PostController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'comment', 'as'=>'comment.'], function(){
        Route::post('/{post_id}/store', [CommentController::class, 'store'])->name('store');
        Route::delete('/{id}/destroy', [CommentController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'profile', 'as'=>'profile.'], function(){
        Route::get('/show', [UserController::class, 'show'])->name('show');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/update', [UserController::class, 'update'])->name('update');
        Route::get('/{userId}/face', [UserController::class, 'face'])->name('face');
    });

});


