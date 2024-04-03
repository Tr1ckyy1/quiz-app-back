<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

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
}
