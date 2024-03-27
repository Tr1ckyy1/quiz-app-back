<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuthRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'username'              => ['required', 'min:3', Rule::unique('users', 'username')],
			'email'                 => ['required', 'email', Rule::unique('users', 'email')],
			'password'              => ['required', 'min:3'],
			'password_confirmation' => ['same:password', 'required'],
			'accept_terms'          => ['accepted'],
		];
	}
}
