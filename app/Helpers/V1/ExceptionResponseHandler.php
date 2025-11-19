<?php

namespace App\Helpers\V1;

use App\Traits\V1\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExceptionResponseHandler
{
    use ResponseTrait;

    private bool $debugMode;

    public function __construct()
    {
        $this->debugMode = config('app.debug', false);
    }

    /**
     * Handle validation exceptions.
     */
    public function handleValidation(\Illuminate\Validation\ValidationException $e, Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->validationErrorResponse($e->errors(), 'Validation failed');
    }

    /**
     * Handle not found exceptions.
     */
    public function handleNotFound(string $message = 'Resource not found'): \Illuminate\Http\JsonResponse
    {
        return $this->notFoundResponse($message);
    }

    /**
     * Handle authentication exceptions.
     */
    public function handleAuthentication(): \Illuminate\Http\JsonResponse
    {
        return $this->unauthorizedResponse('Authentication required');
    }

    /**
     * Handle authorization exceptions.
     */
    public function handleAuthorization(): \Illuminate\Http\JsonResponse
    {
        return $this->forbiddenResponse('Insufficient permissions');
    }

    /**
     * Handle rate limit exceptions.
     */
    public function handleRateLimit(\Illuminate\Http\Exceptions\ThrottleRequestsException $e): \Illuminate\Http\JsonResponse
    {
        $retryAfter = $e->getHeaders()['Retry-After'] ?? null;

        return $this->rateLimitResponse('Too many requests', $retryAfter ? (int) $retryAfter : null);
    }

    /**
     * Handle method not allowed exceptions.
     */
    public function handleMethodNotAllowed(\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e): \Illuminate\Http\JsonResponse
    {
        $allowedMethods = isset($e->getHeaders()['Allow'])
            ? explode(', ', $e->getHeaders()['Allow'])
            : [];

        return $this->methodNotAllowedResponse($allowedMethods, 'Method not allowed');
    }

    public function handleDatabase(\Illuminate\Database\QueryException $e, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->logError('Database error', $e, $request);

        if ($this->debugMode) {
            return $this->serverErrorResponse('Database error: '.$e->getMessage());
        }

        $message = $e->getMessage();

        return match (true) {
            str_contains($message, 'Duplicate entry') => $this->errorResponse('A record with this information already exists', 409, null, 'DUPLICATE_ENTRY'),
            str_contains($message, 'foreign key constraint') => $this->errorResponse('Cannot perform this operation due to related data', 400, null, 'FOREIGN_KEY_CONSTRAINT'),
            str_contains($message, 'Connection refused') => $this->errorResponse('Database connection failed', 503, null, 'DATABASE_CONNECTION_FAILED'),
            default => $this->serverErrorResponse('Database operation failed'),
        };
    }

    public function handleGeneric(Throwable $e, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->logError('Unexpected error', $e, $request);

        $message = $this->debugMode ? $e->getMessage() : 'An unexpected error occurred';

        return $this->serverErrorResponse($message);
    }

    private function logError(string $type, Throwable $e, Request $request): void
    {
        Log::error("API Error: {$type}", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => $request->url(),
            'method' => $request->method(),
            'user_id' => $request->user()?->id,
        ]);
    }

    // Quick handlers
    public function handleCsrfMismatch(): \Illuminate\Http\JsonResponse
    {
        return $this->errorResponse('Invalid CSRF token', 419, null, 'CSRF_MISMATCH');
    }

    public function handleBadRequest(string $message = 'Bad request'): \Illuminate\Http\JsonResponse
    {
        return $this->errorResponse($message, 400, null, 'BAD_REQUEST');
    }

    public function handleTimeout(): \Illuminate\Http\JsonResponse
    {
        return $this->errorResponse('Request timeout', 408, null, 'REQUEST_TIMEOUT');
    }

    public function handleTooLarge(): \Illuminate\Http\JsonResponse
    {
        return $this->errorResponse('Request entity too large', 413, null, 'PAYLOAD_TOO_LARGE');
    }

    public function handleHttpException(\Symfony\Component\HttpKernel\Exception\HttpException $e): \Illuminate\Http\JsonResponse
    {
        $code = $e->getStatusCode();
        $message = $e->getMessage() ?: match ($code) {
            400 => 'Bad request',
            401 => 'Authentication required',
            403 => 'Insufficient permissions',
            404 => 'Resource not found',
            429 => 'Too many requests',
            500 => 'Internal server error',
            default => 'HTTP error occurred',
        };

        return $this->errorResponse($message, $code);
    }
}