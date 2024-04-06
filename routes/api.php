<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
	Route::post('signup', 'signup')->middleware('guest')->name('signup');
	Route::post('login', 'login')->middleware('guest')->name('login');
	Route::post('logout', 'logout')->middleware('auth:sanctum')->name('logout');
	Route::post('forgot-password', 'forgotPassword')->middleware('guest')->name('forgot_password');
	Route::post('reset-password', 'resetPassword')->middleware('guest')->name('reset_password');
});

Route::controller(VerificationController::class)->group(function () {
	Route::get('/email/verify/{id}/{hash}', 'verify')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resend')->middleware('throttle:6,1')->name('verification.send');
});
