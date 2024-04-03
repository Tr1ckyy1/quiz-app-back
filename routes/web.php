<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::view('/', 'signup')->name('signup');
	Route::post('/signup', 'signup')->name('signup');
	Route::view('/log', 'login')->name('login');
	Route::post('/login', 'login')->name('login');
});

Route::get('random', function () {
	return 'testing';
})->middleware('auth');

Route::post('/aa', function () {
	auth()->logout();
});

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');
