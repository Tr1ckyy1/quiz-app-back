<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreResetPasswordRequest;
use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function signup(StoreSignupRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		event(new Registered($user));
		return response()->json(['type' => 'success', 'text' => 'Created Successfully!', 'message' => 'Please check your email address for verification']);
	}

	public function login(StoreLoginRequest $request)
	{
		$credentials = $request->validated();

		if (!auth()->attemptWhen(
			[
				'email'          => $credentials['email'],
				'password'       => $credentials['password'],
			],
			function (User $user) {
				return $user->hasVerifiedEmail();
			},
			$credentials['remember_token'] ?? null
		)) {
			return response()->json(['message' => 'The provided credentials do not match our records, or the user is not verified.'], 403);
		}

		session()->regenerate();
	}

	public function logout()
	{
		auth('web')->logout();
	}

	public function forgotPassword()
	{
		request()->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			request()->only('email')
		);

		return $status === Password::RESET_LINK_SENT
					? response()->json(['type' => 'success', 'text' => 'Resent link sent!', 'message' => __($status)])
					: response()->json(['errors' => ['email' => __($status)]], 422);
	}

	public function resetPassword(StoreResetPasswordRequest $request)
	{
		$status = Password::reset(
			$request->validated(),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);
		switch ($status) {
			case Password::PASSWORD_RESET:
				return response()->json(['type' => 'success', 'text' => 'Success!', 'message' => __($status)]);
			case Password::INVALID_TOKEN:
				return response()->json(['type' => 'error', 'text' => 'Error', 'message' => 'The password reset link is invalid or expired.'], 422);
			default:
				return response()->json(['type' => 'error', 'text' => 'Error', 'message' => __($status)], 422);
		}
	}
}
