<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Rutas públicas
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/categories', [CategoriesController::class, 'index']);




