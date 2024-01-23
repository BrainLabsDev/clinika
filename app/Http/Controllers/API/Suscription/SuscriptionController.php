<?php

namespace App\Http\Controllers\API\Suscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Suscription;

class SuscriptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show/suscripcion/{user}",
     *     summary="Mostrando suscripción del usuario",
     *     operationId="showSuscripcionUser",
     *     tags={"suscripciones"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing suscripcion of the client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(User $user)
    {
        $code = 500;
        $msg = 'Error inesperado';
        $data = null;

        if ($user->hasRole('Usuario')) {

            if ($user->suscripcion == null) {
                $code = 404;
                $msg = 'No hay suscripción';
            } else {
                $end_date = $user->suscripcion->end_date;
                if (date('Y-m-d') > date('Y-m-d', strtotime($end_date))) {
                    $code = 404;
                    $msg = 'La suscripción ha expirado';
                } else {
                    $code = 200;
                    $msg = 'Suscripción valida';
                    $data = [
                        'user' => $user,
                        'suscripcion' => $user->suscripcion,
                    ];
                }
            }
        } else {
             $code = 403;
            $msg = 'Usuario no es apto para tener suscripción';
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/disable/suscripcion/{user}",
     *     summary="Deshabilitando suscripcion del usuario",
     *     operationId="disableSuscripcionUser",
     *     tags={"suscripciones"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Disable suscription of the client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function disable(User $user)
    {
        if ($user->hasRole('Usuario')) {

            $suscripcion = null;

            if ($user->suscripcion == null) {
                $suscripcion = new Suscription();
            } else {
                $suscripcion = $user->suscripcion;
            }

            $suscripcion->start_date = date('Y-m-d',strtotime('-2 month'));
            $suscripcion->end_date = date('Y-m-d', strtotime('-1 month'));
            $suscripcion->user_id = $user->id;
            $suscripcion->save();

            return response()->json([
                'code' => 200,
                'msg' => 'Suscripción deshabilitada',
                'data' => null,
            ]);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'Usuario no es apto para tener suscripción',
                'data' => null,
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/enable/suscripcion/{user}",
     *     summary="Habilitando suscripcion del usuario",
     *     operationId="enableSuscripcionUser",
     *     tags={"suscripciones"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Enable suscription of the client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function enable(User $user)
    {
        if ($user->hasRole('Usuario')) {

            $suscripcion = null;

            if ($user->suscripcion == null) {
                $suscripcion = new Suscription();
            } else {
                $suscripcion = $user->suscripcion;
            }

            $suscripcion->start_date = date('Y-m-d');
            $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
            $suscripcion->user_id = $user->id;
            $suscripcion->save();

            return response()->json([
                'code' => 200,
                'msg' => 'Suscripción habilitada',
                'data' => null,
            ]);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'Usuario no es apto para tener suscripción',
                'data' => null,
            ]);
        }
    }
}
