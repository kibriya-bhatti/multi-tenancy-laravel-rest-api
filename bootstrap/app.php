<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
//use Symfony\Component\HttpKernel\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Define API middleware group
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Alias middleware
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Force JSON for API routes
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*');
        });

        // Authentication exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Request.',
            ], 401);
        });

        // Validation exception
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        });

        // Route not found
        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found.',
                'error' => $e->getMessage() ?: 'HTTP Error',
            ], 404);
        });

        // Resource not found
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'error' => $e->getMessage() ?: 'Page not found',
            ], 404);
        });

        // Generic HTTP exception
        $exceptions->render(function (HttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'HTTP Access Error',
                'error' => $e->getMessage() ?: 'HTTP Error',
            ], $e->getStatusCode());
        });

        $exceptions->render(function(QueryException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Database error Integrity constraint violation',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        });

        // Default fallback
        $exceptions->render(function (Throwable $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        });
    })
    ->create();
