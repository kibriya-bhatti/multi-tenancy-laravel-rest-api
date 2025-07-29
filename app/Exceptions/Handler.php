<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
class Handler extends Exception
{
    public function invalidJson($request, ValidationException $exception) : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors'  => $exception->errors(),
        ], 422);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Default fallback
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
        ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
    }
}
