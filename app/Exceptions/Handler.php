<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;


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

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['status' => 'false',
             'message' => 'Authentication required. Please provide valid credentials or authentication token.', 
             'code' => Response::HTTP_UNAUTHORIZED, // Use constant for status code
        ],
            //     [
            //     'error' => [
            //         'message' => 'Authentication required. Please provide valid credentials or authentication token.',
            //         'code' => Response::HTTP_UNAUTHORIZED, // Use constant for status code
            //     ]
            // ],
             Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson())
        {
            return response()->json([
                'data' => 'Resource not found'
            ], 404);
        }


        return redirect()->guest(route('login')); // Redirect to login page for web requests
    }
}
