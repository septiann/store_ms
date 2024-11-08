<?php

namespace App\Exceptions;

use BadMethodCallException;
use DB;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use PDOException;
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
        if (DB::transactionLevel() > 0) {
            DB::rollback();
        }

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
                'message' => $e->getMessage(),
                'data' => '',
                'errors' => []
            ], 404);
        } else if ($e instanceof BadMethodCallException) {
            return response()->json([
                'success' => false,
                'message' => 'Bad method called',
                'data' => $e->getMessage(),
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
            $modelName = class_basename($e->getModel());

            return response()->json([
                'success' => false,
                'message' => "{$modelName} not found!",
                'data' => '',
                'errors' => []
            ], 404);
        } else if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => "Unauthorized",
                'data' => '',
                'errors' => []
            ], 401);
        } else if ($e instanceof PDOException) {
            return response()->json([
                'success' => false,
                'message' => "There is something happened when connect to database. Please contact our support.",
                'data' => '',
                'errors' => $e
            ], 500);
        } else if ($e instanceof Exception) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => '',
                'errors' => ''
            ], 403);
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
