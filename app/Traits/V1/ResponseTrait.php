<?php

namespace App\Traits\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

trait ResponseTrait
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'status_code' => $code,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }
        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null, ?string $errorCode = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $code,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        if ($errorCode !== null) {
            $response['error_code'] = $errorCode;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a paginated response.
     */
    protected function paginatedResponse($paginator, ?string $message = null): JsonResponse
    {
        $response = [
            'success' => true,
            'status_code' => 200,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }

    /**
     * Return a created response.
     */
    protected function createdResponse($data = null, string $message = 'Created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Return a no content response.
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return a not found response.
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Return an unauthorized response.
     */
    protected function unauthorizedResponse(string $message = 'Authentication required'): JsonResponse
    {
        return $this->errorResponse($message, 401, null, 'UNAUTHENTICATED');
    }

    /**
     * Return a forbidden response.
     */
    protected function forbiddenResponse(string $message = 'Insufficient permissions'): JsonResponse
    {
        return $this->errorResponse($message, 403, null, 'FORBIDDEN');
    }

    /**
     * Return a validation error response.
     */
    protected function validationErrorResponse(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors, 'VALIDATION_FAILED');
    }

    /**
     * Return a rate limit exceeded response.
     */
    protected function rateLimitResponse(string $message = 'Too many requests', ?int $retryAfter = null): JsonResponse
    {
        $data = [
            'success' => false,
            'message' => $message,
            'status_code' => 429,
            'error_code' => 'RATE_LIMIT_EXCEEDED',
        ];

        if ($retryAfter !== null) {
            $data['retry_after'] = $retryAfter;
        }

        $response = response()->json($data, 429);

        if ($retryAfter !== null) {
            $response->header('Retry-After', $retryAfter);
        }

        return $response;
    }

    /**
     * Return a server error response.
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    /**
     * Return a method not allowed response.
     */
    protected function methodNotAllowedResponse(array $allowedMethods = [], string $message = 'Method not allowed'): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => 405,
            'error_code' => 'METHOD_NOT_ALLOWED',
        ];

        if ($allowedMethods) {
            $response['allowed_methods'] = $allowedMethods;
        }

        return response()->json($response, 405);
    }

    /**
     * Add meta information conditionally.
     */
    private function addMetaIfNeeded(array $response): array
    {
        if (config('app.debug') || request()->boolean('include_meta')) {
            $response['meta'] = [
                'timestamp' => now()->toISOString(),
                'request_id' => request()->header('X-Request-ID') ?? Str::uuid()->toString(),
            ];
        }

        return $response;
    }

    protected function successResponseWithMeta($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'status_code' => $code,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }
        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($this->addMetaIfNeeded($response), $code);
    }

    protected function bulkResponse(array $results, string $message = 'Bulk operation completed'): JsonResponse
    {
        $successful = count(array_filter($results, fn ($result) => $result['success'] ?? false));
        $total = count($results);

        return $this->successResponse([
            'results' => $results,
            'summary' => [
                'total' => $total,
                'successful' => $successful,
                'failed' => $total - $successful,
            ],
        ], $message);
    }

    /**
     * Quick response for boolean operations.
     */
    protected function booleanResponse(bool $result, string $successMessage = 'Operation successful', string $errorMessage = 'Operation failed'): JsonResponse
    {
        return $result
            ? $this->successResponse(null, $successMessage)
            : $this->errorResponse($errorMessage);
    }

    /**
     * Quick response for count operations.
     */
    protected function countResponse(int $count, ?string $message = null): JsonResponse
    {
        return $this->successResponse(['count' => $count], $message);
    }

    /**
     * Quick response for exists operations.
     */
    protected function existsResponse(bool $exists, ?string $message = null): JsonResponse
    {
        return $this->successResponse(['exists' => $exists], $message);
    }
}