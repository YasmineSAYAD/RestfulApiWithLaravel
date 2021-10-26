<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Request;
use Response;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthentificationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Barryvdh\Cors\CorsService;

class Handler extends ExceptionHandler
{
use ApiResponser;
    // A list of the exception types that are not reported.
    protected $dontReport = [
        \Illuminate\Auth\AuthentificationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
    public function report(Throwable $exception){
      parent::report($exception);
    }
    public function render($request,Throwable $exception){

        $response=$this->handleException($request,$exception);
        app(CorsService::class)->addActualRequestHeaders($response,$request);
        return $response;

    }

    public function handleException($request,Throwable $exception){

        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        if($exception instanceof AuthentificationException){
            return $this->errorResponse($exception->getMessage(), 401);
        }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('The specified url cannot be found', 404);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('The specified method for the requestes is invalid', 405);
        }
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if($exception instanceof QueryException){
            $errorCode=$exception->errorInfo[1];
            if($errorCode==1451){
                return $this->errorResponse('Cannot remove this ressource permanently. It related with any other ressource',409);
            }

        }
        if($exception instanceof ModelNotFoundException){
            $modelName=strtolower(class_basename($exception->getModel()));
            return $this->errorResponse('Does not exists any '.$modelName.' with the specified identificator',404);
        }
        //token exception in web application
        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());

         }
        //changer debug app in .env to false
        if(config('app.debug')){
            return parent::render($request,$exception);
        }
      return $this->errorResponse('Unexcepted Exception. Try later',500);
    }
/*
    protected function unauthenticated($request,AuthenticationException $exception){
        if($this->isFrontend($request)){
           return redirect()->guest('login');
        }
        return $this->errorResponse('Unauthenticated.', 401);

    }*/
   /*
    // Register the exception handling callbacks for the application.
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    */
    public function convertValidationExceptionToResponse(ValidationException $exception,$request){
        $errors=$exception->validator->errors()->getMessages();
        /*
        if($this->isFrontend($request)){
          return $request->ajax() ? response()->json($error,422) :
          redirect()->back()->withInput($request->input())->withErrors($errors);
        }*/

        return $this->errorResponse($errors, 422);
    }
    /*
    private function isFrontend($request){
        //if the request  is a web request
       return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }*/
}
