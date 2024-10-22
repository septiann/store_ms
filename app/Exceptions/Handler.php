<?php

namespace App\Exceptions;

use BadMethodCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e) {
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'This method is not allowed for the requested route',
                'data' => '',
                'errors' => []
            ], 405);
        } else if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'This route is not found',
                'data' => '',
                'errors' => []
            ], 404);
        } else if ($e instanceof BadMethodCallException) {
            return response()->json([
                'success' => false,
                'message' => 'Bad method called',
                'data' => '',
                'errors' => []
            ], 404);
        } else if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed!',
                'data' => '',
                'errors' => $e->errors()
            ], 422);
        } else if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found!',
                'data' => '',
                'errors' => []
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is something happened. Please contact our support.',
                'data' => '',
                'errors' => $request . $e
            ], 500);
        }
    }
}
