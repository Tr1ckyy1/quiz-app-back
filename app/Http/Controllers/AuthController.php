<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function signup(StoreSignupRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		event(new Registered($user));
		return response()->json('Please check your email for verification');
	}

	public function login(StoreLoginRequest $request)
	{
		$credentials = $request->validated();

		if (!auth()->attempt($credentials)) {
			return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
		}

		session()->regenerate();

		return response()->json(['message' => 'logged']);
	}

	public function logout()
	{
		return response()->json(['message' => 'blabla']);
		// auth()->logout();
	}
}
