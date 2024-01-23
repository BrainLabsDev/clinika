<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Throwable;

use App\Traits\ApiMessages;
use App;

class Handler extends ExceptionHandler
{
    use ApiMessages;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {   
        //dd($exception);
        if(stristr($request->getPathInfo(), 'api')){
            if($exception instanceof ValidationException){
                //dd('here');
                //return $this->convertValidationExceptionToResponse($exception,$request);
                return $this->errorResponse($exception->getMessage(),$exception->errors(),$exception->status);
            }
            if($exception instanceof ModelNotFoundException){
                $modelo = class_basename($exception->getModel());
                return $this->errorResponse('error','No existe ninguna instancia de '.$modelo.' con el id solicitado',404);
            }
            if($exception instanceof AuthenticationException){
                //return $this->unauthenticated($request,$exception);
                return $this->errorResponse('error','No Autenticado',401);
            }
            if($exception instanceof AuthorizationException){
                return $this->errorResponse('error','No posees permisos para esta acción',403);
            }
            if($exception instanceof NotFoundHttpException){
                return $this->errorResponse('error','No se encontró la URL especificada',404);
            }
            if($exception instanceof MethodNotAllowedHttpException){
                return $this->errorResponse('error','El método especificado en la peticion no es valido',405);
            }
            if($exception instanceof HttpException){
                return $this->errorResponse('error',$exception->getMessage(),$exception->getStatusCode());
            }
            if($exception instanceof QueryException){
                $codigo = $exception->errorInfo[1];
                if($codigo == 1451){
                    return $this->errorResponse('error','No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro',409);
                }
                if($codigo == 1045){
                    return $this->errorResponse('error','Credenciales incorrectas al conectarse a la BD '.$exception,409);
                }
            }
            //return $this->errorResponse('error','Falla inesperada. Intente Luego',500);
            return $this->errorResponse('error','Falla inesperada. Intente Luego'.$exception,500);
        }

        // 404 page with status code 200
        if ($exception instanceof NotFoundHttpException) {
            return redirect()->route('not-found', App::getLocale());
        }

        return parent::render($request, $exception);
        

    }

    protected function unauthenticated($request,AuthenticationException $exception){
        //return $this->errorResponse('error','No Autenticado',401);
        //return parent::render($request, $exception);
        return redirect()->route('no-authorized',App::getLocale());
    }
}
