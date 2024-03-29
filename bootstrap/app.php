<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		commands: __DIR__ . '/../routes/console.php',
		api: __DIR__ . '/../routes/api.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InvalidSignatureException $e) {
			return new RedirectResponse(config('app.frontend_url') . '/auth/login?verification=expired&type=warning&text=Token expired&message=Your email verification token has expired. Please try again');
		});
	})->create();
