<?php

use App\Http\Middleware\RestrictAuthorizedUsers;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		commands: __DIR__ . '/../routes/console.php',
		api: __DIR__ . '/../routes/api.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
		$middleware->alias([
			'guest' => RestrictAuthorizedUsers::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InvalidSignatureException $e) {
			return response()->json(['message' => 'Email verification token expired or invalid verification link.'], 404);
		});
	})->create();
