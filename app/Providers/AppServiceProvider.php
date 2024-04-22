<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot()
	{
		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			$expiration = now()->addMinutes(config('auth.verification.expire'));
			$signedUrl = URL::temporarySignedRoute(
				'verification.verify',
				$expiration,
				['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
			);

			$urlParts = parse_url($signedUrl);
			$query = isset($urlParts['query']) ? $urlParts['query'] : '';
			parse_str($query, $queryParams);
			$expires = $queryParams['expires'] ?? null;
			$signature = $queryParams['signature'] ?? null;

			$frontendUrl = config('app.frontend_url') . '/auth/login' . '?' . http_build_query([
				'id'        => $notifiable->getKey(),
				'hash'      => sha1($notifiable->getEmailForVerification()),
				'expires'   => $expires,
				'signature' => $signature,
			]);

			return (new MailMessage)->view('auth.verify-email', ['url' => $frontendUrl, 'user' => $notifiable->username, 'headerText' => 'Verify your email address to get started', 'text' => "You're almost there! To complete your sign up, please verify your email address.", 'buttonText' => 'Verify now'])->subject('Please verify your email')->from(env('MAIL_FROM_ADDRESS'), 'no-reply@quizwiz.com');
		});

		Model::preventLazyLoading();
	}
}
