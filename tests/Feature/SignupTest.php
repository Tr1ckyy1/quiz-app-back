<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SignupTest extends TestCase
{
	use RefreshDatabase;

	public function test_user_able_to_signup_successfully_with_provided_credentials()
	{
		$response = $this->postJson(route('signup'), ['email' => 'testing@gmail.com', 'username' => 'testing', 'password'=>'pass', 'password_confirmation' => 'pass', 'accept_terms' => 1]);
		$response
		->assertStatus(200)
		->assertExactJson([
			'type'    => 'success',
			'text'    => 'Created Successfully!',
			'message' => 'Please check your email address for verification',
		]);
	}

	public function test_user_signup_sends_verification_email()
	{
		Notification::fake();
		$userData = [
			'email'                 => 'testing@gmail.com',
			'username'              => 'testing',
			'password'              => 'pass',
			'password_confirmation' => 'pass',
			'accept_terms'          => 1,
		];

		$response = $this->postJson(route('signup'), $userData);

		$response->assertStatus(200);

		$this->assertDatabaseHas('users', [
			'email'    => 'testing@gmail.com',
			'username' => 'testing',
		]);

		$user = User::where('email', $userData['email'])->first();

		Notification::assertSentTo($user, VerifyEmail::class);
	}

	public function test_email_verification_redirects_to_frontend_and_confirms_user()
	{
		Notification::fake();

		$user = User::factory()->create(['email_verified_at' => null]);

		$expiration = now()->addMinutes(config('auth.verification.expire'));
		$signedUrl = URL::temporarySignedRoute(
			'verification.verify',
			$expiration,
			['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
		);

		$response = $this->getJson($signedUrl);
		$response->assertJson(['message' => 'Your account has been verified successfully!']);
		$response->assertStatus(200);
	}

	public function test_signup_should_return_error_if_authorized_user_is_trying_to_sign_up()
	{
		$user = User::factory()->create();
		$response = $this->actingAs($user)->postJson(route('signup'));
		$response->assertStatus(401);
	}

	public function test_signup_should_have_error_if_inputs_are_not_provided()
	{
		$response = $this->postJson(route('signup'));
		$response->assertJsonValidationErrors(['username', 'email', 'password', 'password_confirmation', 'accept_terms']);
		$response->assertStatus(422);
	}

	public function test_signup_username_should_have_error_if_input_length_is_less_than_three_characters()
	{
		$response = $this->postJson(route('signup'), ['username' => 'ec']);
		$response->assertJsonValidationErrors(['username' => 'The username field must be at least 3 characters.']);
		$response->assertStatus(422);
	}

	public function test_signup_username_should_have_error_if_user_is_already_taken()
	{
		User::factory()->create(['username' => 'username']);
		$response = $this->postJson(route('signup'), ['username' => 'username']);
		$response->assertJsonValidationErrors(['username' => 'The username has already been taken.']);
		$response->assertStatus(422);
	}

	public function test_signup_email_should_have_error_if_input_is_not_email()
	{
		$response = $this->postJson(route('signup'), ['email' => 'testgmail.com']);
		$response->assertJsonValidationErrors('email');
		$response->assertStatus(422);
	}

	public function test_signup_email_should_have_error_if_email_is_already_taken()
	{
		User::factory()->create(['email' => 'testing@redberry.ge']);
		$response = $this->postJson(route('signup'), ['email' => 'testing@redberry.ge']);
		$response->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
		$response->assertStatus(422);
	}

	public function test_password_should_have_error_if_input_length_is_less_than_three_characters()
	{
		$response = $this->postJson(route('signup'), ['password' => 'ec']);
		$response->assertJsonValidationErrors(['password' => 'The password field must be at least 3 characters.']);
		$response->assertStatus(422);
	}

	public function test_password_confirmation_should_have_error_if_it_does_not_match_password()
	{
		$response = $this->postJson(route('signup'), ['password' => 'etc', 'password_confirmation' => 'bla']);
		$response->assertJsonValidationErrors(['password_confirmation' => 'The password confirmation field must match password.']);
		$response->assertStatus(422);
	}

	public function test_accept_terms_should_have_error_if_it_is_not_accepted()
	{
		$response = $this->postJson(route('signup'), ['accept_terms' => '0']);
		$response->assertJsonValidationErrors(['accept_terms' => 'The accept terms field must be accepted.']);
		$response->assertStatus(422);
	}
}
