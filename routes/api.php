<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Rutas públicas
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);

Route::get('/categories', [CategoriesController::class, 'index']);
Route::get ('/categories/{id}', [CategoriesController::class, 'show']);


//Rutas protegidas
Route::post('/products', [ProductsController::class, 'store']);
Route::post('/categories', [CategoriesController::class, 'store']);


