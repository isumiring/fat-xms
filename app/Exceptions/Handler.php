<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use \Symfony\Component\HttpKernel\Exception\ErrorException;

use Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $error_404_template = 'errors.404';
        $error_500_template = 'errors.500';
        if ($request->segment(1) == backend_url()) {
            $error_404_template = backend_path('.errors.404');
            $error_500_template = backend_path('.errors.500');
        }
        if ($this->isHttpException($exception)) {
            if ($request->segment(1) == backend_url()) {
                if ( ! Auth::guard(backend_guard())->check()) {
                    return redirect()->route(backend_path('.auth.login'));
                }
            }
            if ($exception instanceof NotFoundHttpException) {
                return response()->view($error_404_template, [], 404);
                // abort(404);
            }
            return $this->renderHttpException($exception);
        }
        if (app()->environment('local')) {
            if ($exception instanceof \ErrorException) {

                // return response()->view($error_500_template, [], 500);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
