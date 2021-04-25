<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use function Psy\debug;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->renderable(function (\Exception $e, $request) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->back()->withInput($request->input());
            }
            if ($e->getPrevious()  instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $model = strtolower(class_basename($e->getPrevious()->getModel()));
                return $this->errorResponse('Doesnot exist any ' . $model . '  with the specified identificator', 404);
            }
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->validator->errors()->getMessages();
                if ($this->isFrontend($request)) {
                    return $request->ajax()? response()->json($errors,422):
                    redirect()->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
                }
                return $this->errorResponse($errors, 422);
            }
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return $this->errorResponse('The specified URL cannot be found', 404);
            }

            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return $this->errorResponse($e->getMessage(), 403);
            }
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->unauthenticated($request, $e);
            }
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return $this->errorResponse('The specified method for the request is invalid', 405);
            }
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return $this->errorResponse($e->getMessage(), $e->getStatusCode());
            }
            if ($e instanceof \Illuminate\Database\QueryException) {
                if ($e->errorInfo[1] == 1451) {
                    return $this->errorResponse('cannot remove this resource permanently ,It is related with other resource', 409);
                }
            }


            if (!config('app.debug')) {

                return $this->errorResponse('Unexpected Exception. try later', 500);
            }
        });
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }
        return $this->errorResponse($exception->getMessage(), 401);
    }
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
