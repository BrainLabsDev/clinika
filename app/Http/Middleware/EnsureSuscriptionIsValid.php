<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuscriptionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() != null && Auth::user()->hasRole('Usuario')) {
            if (Auth::user()->suscripcion == null) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'No cuenta con suscripción',
                    'data' => null,
                ]);
            } else {
                $end_date = Auth::user()->suscripcion->end_date;
                if (date('Y-m-d') > date('Y-m-d', strtotime($end_date))) {
                    return response()->json([
                        'code' => 404,
                        'msg' => 'La suscripción ha expirado',
                        'data' => null,
                    ]);
                } else {
                    return $next($request);
                }
            }
        } else {
            if (\Request::getRequestUri() == '/api/login') {

                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ]);

                if (Auth::attempt($credentials)) {
                    if (Auth::user()->hasRole('Usuario')) {
                        if (Auth::user()->suscripcion == null) {
                            return response()->json([
                                'code' => 404,
                                'msg' => 'No cuenta con suscripción',
                                'data' => null,
                            ]);
                        } else {
                            $end_date = Auth::user()->suscripcion->end_date;
                            if (date('Y-m-d') > date('Y-m-d', strtotime($end_date))) {
                                return response()->json([
                                    'code' => 404,
                                    'msg' => 'La suscripción ha expirado',
                                    'data' => null,
                                ]);
                            } else {
                                return $next($request);
                            }
                        }
                    } else {
                        return $next($request);
                    }
                } else {
                    return $next($request);
                }
            } else {
                return $next($request);
            }

        }
    }
}
