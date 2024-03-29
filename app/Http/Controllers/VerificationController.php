<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function verify(Request $request, $id, $hash)
	{
		$user = User::findOrFail($id);
		if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
			return response()->json(['message' => 'Invalid verification link or user not found.'], 404);
		}
		if ($user->hasVerifiedEmail()) {
			return redirect(config('app.frontend_url') . '/auth/login?verification=failed&type=warning&text=Verified already!&message=Your account has already been verified!');
		} else {
			$user->markEmailAsVerified();
			return redirect(config('app.frontend_url') . '/auth/login?verification=success&type=success&text=Verified Successfully&message=Your account has been verified successfully!');
		}
	}
}
