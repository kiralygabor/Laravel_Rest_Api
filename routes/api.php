<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;     

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/users/login', [UserController::class, 'login']); 
Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store'])->middleware('auth:sanctum');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum'); 

Route::get('/authors', [AuthorController::class, 'index']); 
Route::post('/authors', [AuthorController::class, 'store'])->middleware('auth:sanctum');
Route::put('/authors/{id}', [AuthorController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/books', [BookController::class, 'index']); 
Route::post('/books', [BookController::class, 'store'])->middleware('auth:sanctum');
Route::put('/books/{id}', [BookController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->middleware('auth:sanctum');



