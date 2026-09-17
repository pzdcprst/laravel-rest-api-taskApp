<?php

use App\Exceptions\DueDateException\InvalidDueDateException;
use App\Exceptions\DueDateException\TaskDeadLineLockedException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\TaskExceptions\TaskAlreadyCompletedException;
use App\Exceptions\TaskExceptions\TaskCannotBeCancelledException;
use App\Exceptions\TaskExceptions\InvalidTaskStatusTransitionException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            NotFoundHttpException $exception,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not found',
                ], 404);
            }
        });

        $exceptions->render(function (
            TaskAlreadyCompletedException|
            TaskCannotBeCancelledException|
            InvalidTaskStatusTransitionException|
            InvalidDueDateException|
            TaskDeadLineLockedException $exception,
            Request $request
        ) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    //'errors' => $exception->getErrors(),
                ], $exception->getCode() ?: 409);
            }

            return null;
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Resource not found',
            ]);
        });

        $exceptions->render(function (AuthorizationException $exception) {
            return response()->json([
                'message' => $exception->getMessage() ?: "Forbbiden",
            ], 403);
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return response()->json([
                'message' => "Unauthorized",
            ], 401);
        });

        $exceptions->render(function (ValidationException $exception) {
            return response()->json([
                'message' => "Validation failed",
                'errors' => $exception->errors(),
            ], 422);
        });

        $exceptions->render(function (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        });

    })->create();
