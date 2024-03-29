<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
	Route::view('/', 'signup')->name('signup');
	Route::post('/signup', 'signup')->name('signup');
});

// Route::get('/email/verify', function () {
// 	return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
// 	$request->fulfill();

// 	return redirect('/profile');
// })->middleware('signed')->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');

Route::get('/profile', function () {
	return 'hello guys this is profile page, therefore, user is verified';
	// Only verified users may access this route...
})->middleware(['auth', 'verified']);

// Route::post('/email/verification-notification', function (Request $request) {
// 	$request->user()->sendEmailVerificationNotification();
// 	return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
