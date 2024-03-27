<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::view('/', 'signup')->name('signup');
	Route::post('/signup', 'signup')->name('signup');
});
