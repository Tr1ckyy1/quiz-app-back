<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function signup(StoreAuthRequest $request)
	{
		$user = User::create($request->validated());
		event(new Registered($user));
		return response()->json('Please check your email for verification');
	}
}
