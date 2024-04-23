<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
	use Queueable;

	protected $url;

	/**
	 * Create a new notification instance.
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage)->view('auth.verify-email', ['url' => $this->url, 'user' => $notifiable->username, 'headerText' => 'Reset your password to proceed', 'text' => 'You are receiving this email because we received a request to reset the password for your account. If you did not request this change, you can safely ignore this email.', 'buttonText' => 'Reset password'])->subject('Reset your password')->from(env('MAIL_FROM_ADDRESS'), 'no-reply@quizwiz.com');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
