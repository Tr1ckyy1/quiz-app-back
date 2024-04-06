<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSanctumRequest
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		return $next($request);
	}

	protected function storePasswordHashInSession($request, string $guard)
	{
		if (!auth($guard)->user()) {
			return;
		}

		$request->session()->put([
			"password_hash_{$guard}" => $request->user()->getAuthPassword(),
		]);
	}
}
