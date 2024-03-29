<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::post('signup', [AuthController::class, 'signup'])->name('signup');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

// Route::post('email/verification-notification', function (Request $request) {
// 	$request->user()->sendEmailverificationNotification();
// });

// Route::get('/email/verify', function () {
// 	return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/profile', function () {
// 	return 'hello guys this is profile page, therefore, user is verified';
// 	// Only verified users may access this route...
// })->middleware(['auth', 'verified']);
