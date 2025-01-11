<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

/**
 * Class Handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof UnauthorizedException) {
            if ($request->is('api/*') || $request->wantsJson())
            {
                $json = [
                    'errors' => [
                        [
                            'key' => $exception->getCode(),
                            'error' => $exception->getMessage(),
                        ]
                    ],
                ];
                return response()->json($json, 403);
            }

            return redirect()
                ->route(homeRoute())
                ->withFlashDanger(__('You do not have access to do that.'));
        }

        if ($exception instanceof AuthorizationException) {

            if ($request->is('api/*') || $request->wantsJson())
            {
                $json = [
                    'errors' => [
                        [
                            'key' => $exception->getCode(),
                            'error' => $exception->getMessage(),
                        ]
                    ],
                ];
                return response()->json($json, 403);
            }

            return redirect()
                ->back()
                ->withFlashDanger($exception->getMessage() ?? __('You do not have access to do that.'));
        }

        if ($exception instanceof ModelNotFoundException) {
            return redirect()
                ->route(homeRoute())
                ->withFlashDanger(__('The requested resource was not found.'));
        }

        return parent::render($request, $exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['errors' => [
                [
                    'key' => $exception->getCode(),
                    'error' => $exception->getMessage(),
                ]
            ]], 401);
        }

        return redirect()->guest(route('frontend.auth.login'));
    }
}
