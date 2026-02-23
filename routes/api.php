<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsUserAuth;

//publicas - puede entrar todo el mundo aunque no tenga TOKEN
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/students/{id}', [StudentController::class, 'soloId']);
Route::post ('/register', [AuthController::class, 'register']);
Route::post ('/login', [AuthController::class, 'login']);


//protegidas con auth

Route::middleware([IsUserAuth::class])->group(function () {

Route::get('profile', [AuthController::class, 'getUser']);
Route::post ('logout', [AuthController::class, 'logout']);
Route::get('/students', [StudentController::class, 'index']);

});


