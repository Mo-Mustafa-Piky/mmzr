<?php

use App\Helpers\V1\ExceptionResponseHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api_v1.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
        then: function () {
            // إضافة نسخة v2
            Route::prefix('api/v2')->group(function () {
                require __DIR__.'/../routes/api_v2.php';
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // // Note: Spatie Response Cache is applied via route middleware for selective caching

        // $middleware->alias([
        //     'cacheResponse' => \Spatie\ResponseCache\Middlewares\CacheResponse::class,
        //     'doNotCacheResponse' => \Spatie\ResponseCache\Middlewares\DoNotCacheResponse::class,
        //     'validate.api.input' => \App\Http\Middleware\V1\ValidateApiInput::class,
        //     'auth.optional' => \App\Http\Middleware\V1\OptionalAuth::class,
        //     'update.last.logged.in' => \App\Http\Middleware\UpdateLastLoggedIn::class,
        // ]);
        
        // $middleware->prependToGroup('api', \App\Http\Middleware\V1\AlwaysAcceptJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $handler = new ExceptionResponseHandler;

        $exceptions->renderable(function (ValidationException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleValidation($e, $request) : null;
        });
        $exceptions->renderable(function (AuthenticationException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleAuthentication() : null;
        });
        $exceptions->renderable(function (AuthorizationException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleAuthorization() : null;
        });
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleNotFound('Resource not found') : null;
        });
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleNotFound('Endpoint not found') : null;
        });
        $exceptions->renderable(function (ThrottleRequestsException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleRateLimit($e) : null;
        });
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleMethodNotAllowed($e) : null;
        });
        $exceptions->renderable(function (QueryException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleDatabase($e, $request) : null;
        });
        $exceptions->renderable(function (TokenMismatchException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleCsrfMismatch() : null;
        });
        $exceptions->renderable(function (HttpException $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleHttpException($e) : null;
        });
        $exceptions->renderable(function (\Throwable $e, Request $request) use ($handler) {
            return $request->is('api/*') ? $handler->handleGeneric($e, $request) : null;
        });
    })
    ->create();