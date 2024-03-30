<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
	public function verify(Request $request, $id, $hash)
	{
		$user = User::findOrFail($id);
		if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
			return response()->json(['message' => 'Invalid verification link or user not found.'], 404);
		}

		if ($user->hasVerifiedEmail()) {
			return response()->json(['type' => 'warning', 'text' => 'Already verified!', 'message' => 'Your account has already been verified!!', 'duration' => 4000]);
		} else {
			$user->markEmailAsVerified();
			return response()->json(['type' => 'success', 'text' => 'Verified Successfully', 'message' => 'Your account has been verified successfully!', 'duration' => 3000]);
		}
	}

	public function resend(Request $request)
	{
		$id = $request->input('id');
		$expires = $request->input('expires');

		$user = User::find($id);

		if (!$user) {
			return response()->json(['message' => 'User not found'], 404);
		}

		if (Carbon::now()->gte(Carbon::createFromTimestamp($expires)) && !$user->hasVerifiedEmail()) {
			$user->sendEmailVerificationNotification();
			return response()->json(['type' => 'success', 'text' => 'Verification Resent', 'message' => 'Verification email resent', 'duration' => 3000]);
		}

		if ($user->hasVerifiedEmail()) {
			return response()->json(['type' => 'warning', 'text' => 'Already verified!', 'message' => 'Email already verified', 'duration' => 4000]);
		}
		$validSignature = URL::hasValidSignature($request);
		if (!$validSignature) {
			return response()->json(['message' => 'Invalid signature'], 422);
		}
	}
}
