<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthRequest;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function signup(StoreAuthRequest $request)
	{
		$user = User::create($request->validated());
		// $user->sendEmailVerificationNotification();
		event(new Registered($user));
		// $user->notify(new VerifyEmailNotification($user));
		// $user->notify(new VerifyEmailNotification);

		auth()->login($user);

		// session()->regenerate();

		// return redirect('/');
		return response()->json('Please check your email for verification');
	}
}
