<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyLevelController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizwizInfoController;
use App\Http\Controllers\VerificationController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::get('/quizwiz-info', [QuizwizInfoController::class, 'show'])->name('quizwiz_info');

Route::controller(QuizController::class)->group(function () {
	Route::get('/quizzes', 'index')->name('quizzes.index');
	Route::get('/quizzes/{quiz}', 'show')->name('quizzes.show');
	Route::get('/similar-quizzes', 'similarQuizzes')->name('similar_quizzes');
	Route::post('/submit-quiz', 'store')->name('complete_quiz');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/difficulty-levels', [DifficultyLevelController::class, 'index'])->name('diffulty_levels');

Route::controller(AuthController::class)->group(function () {
	Route::post('/signup', 'signup')->middleware('guest')->name('signup');
	Route::post('/login', 'login')->middleware('guest')->name('login');
	Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
	Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('forgot_password');
	Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('reset_password');
});

Route::controller(VerificationController::class)->group(function () {
	Route::get('/email/verify/{id}/{hash}', 'verify')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resend')->middleware('throttle:6,1')->name('verification.send');
});
