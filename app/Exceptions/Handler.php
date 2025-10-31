<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->wantsJson()) {

            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => $e->errors(),
                ], 422);
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Пользователь не найден'], 404);
            }

            if ($e instanceof ConflictHttpException) {
                return response()->json(['message' => $e->getMessage() ?: 'Conflict'], 409);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json(['message' => 'Method not allowed'], 405);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            return response()->json(['message' => 'Internal server error'], 500);
        }

        return parent::render($request, $e);
    }
}