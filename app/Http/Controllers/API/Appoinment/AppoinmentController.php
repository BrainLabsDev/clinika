<?php

namespace App\Http\Controllers\API\Appoinment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appoinment;
use App\Models\Room;
use App\Models\Suscription;
use App\Models\User;
use App\Models\NutritionEquivalent;

class AppoinmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show-history/cita-control/{user}",
     *     summary="Mostrando historial de cita de control del cliente",
     *     operationId="showHistorialCitaControlUser",
     *     tags={"cita-control"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing history of citas of control of the Client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function index(User $user)
    {
        $code = 500;
        $msg = 'Error inesperado';
        $data = null;

        $citas = Appoinment::where('client_id', $user->id)->orderByDesc('date')->get();
        if ($citas->isEmpty()) {
            $code = 404;
            $msg = 'No hay citas de control';
        } else {
            $code = 200;
            $msg = 'Mostrando historial de citas de control';
            $data = $citas;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/cita-control/{user}",
     *     summary="Mostrando cita de control del cliente",
     *     operationId="showCitaControlUser",
     *     tags={"cita-control"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing cita of control of the Client"),
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

        $cita = Appoinment::where('client_id', $user->id)->orderByDesc('date')->first();
        if ($cita == null) {
            $code = 404;
            $msg = 'No hay citas de control';
        } else {
            $code = 200;
            $msg = 'Mostrando cita de control actual';
            $data = $cita;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/cita-control",
     *     summary="Crear cita de control",
     *     operationId="createCitaControl",
     *     tags={"cita-control"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="peso",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="musculo",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="grasas",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="porcentaje_grasa",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="cc",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="grasa_viceral",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="evolucion",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="cliente_id",
     *                  type="integer"
     *              ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Cita de control created"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function store(Request $request)
    {
        $rules = [
            'peso' => 'required|string',
            'musculo' => 'required|string',
            'grasas' => 'required|string',
            'porcentaje_grasa' => 'required|string',
            'cc' => 'required|string',
            'grasa_viceral' => 'required|string',
            'evolucion' => 'required|string',
            'cliente_id' => 'required|string',
        ];

        $messages = [
            'peso.required' => 'El peso es requerido',
            'peso.string' => 'El peso debe ser un número',
            'musculo.required' => 'El porcentaje de musculo es requerido',
            'musculo.string' => 'El porcentaje de musculo debe ser un número',
            'grasas.required' => 'El porcentaje de grasas es requerido',
            'grasas.string' => 'El porcentaje de grasas debe ser un número',
            'porcentaje_grasa.required' => 'El porcentaje de grasa es requerido',
            'porcentaje_grasa.string' => 'El porcentaje de grasa debe ser un número',
            'cc.required' => 'El CC es requerido',
            'cc.string' => 'El CC debe ser un número',
            'grasa_viceral.required' => 'El porcentaje de grasa visceral es requerido',
            'grasa_viceral.string' => 'El porcentaje de grasa visceral debe ser un número',
            'evolucion.required' => 'La evolución es requerida',
            'evolucion.string' => 'La evolución debe ser un texto',
            'cliente_id.required' => 'El cliente es requerido',
            'cliente_id.string' => 'El cliente debe ser un número',
        ];

        $this->validate($request, $rules, $messages);

        $cliente = User::find($request->cliente_id);
        if ($cliente == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El cliente no existe',
                'data' => null,
            ]);
        }

        if (!$cliente->hasRole('Usuario')) {
            return response()->json([
                'code' => 403,
                'msg' => 'No se le puede asignar una cita a este Usuario',
                'data' => null,
            ]);
        }

        $nutricionista = User::find($cliente->nutricionist_id);
        if ($nutricionista == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El nutricionista no existe',
                'data' => null,
            ]);
        }

        if($cliente->suscripcion == null) {
            //En caso de no tener un suscripcion activa
            //Se procede a crear una suscripcion
            $suscripcion = new Suscription();
            $suscripcion->start_date = date('Y-m-d');
            $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
            $suscripcion->user_id = $cliente->id;
            $suscripcion->save();
        } else {
            if (date('Y-m-d') > $cliente->suscripcion->end_date) {
                //En caso de tener una suscripcion pero esta haya expirado
                //Se procede a actualizar la fecha de expiracion
                $cliente->suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
                $cliente->suscripcion->save();
            } else {
                // En caso de que no haya expirado la suscripcion, se procede a sobreescribirla
                $suscripcion = Suscription::where('user_id',$cliente->id)->first();
                $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
                $suscripcion->save();
            }
        }


        $cita = new Appoinment();
        $cita->date = now();
        $cita->weight = (double) $request->peso;
        $cita->muscle = (double) $request->musculo;
        $cita->fat = (double) $request->grasas;
        $cita->average_fat = (double) $request->porcentaje_grasa;
        $cita->cc = (double) $request->cc;
        $cita->viseral_fat = (double) $request->grasa_viceral;
        $cita->evolution = $request->evolucion;
        $cita->client_id = $cliente->id;
        $cita->nutricionist_id = $nutricionista->id;
        $cita->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita creada correctamente',
            'data' => [
                'cita' => $cita,
                'cliente' => $cliente,
                'suscripcion' => $cliente->suscripcion,
                'nutricionista' => $nutricionista,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/cita-control/{cita}",
     *     summary="Actualizar cita de control",
     *     operationId="updateCitaControl",
     *     tags={"cita-control"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="cita",
     *         in="path",
     *         description="Id of the Cita de Control",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="peso",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="musculo",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="grasas",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="porcentaje_grasa",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="cc",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="grasa_viceral",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="evolucion",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="cliente_id",
     *                  type="integer"
     *              ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Cita de control updated"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function update(Request $request, Appoinment $cita)
    {
        $rules = [
            'peso' => 'required|string',
            'musculo' => 'required|string',
            'grasas' => 'required|string',
            'porcentaje_grasa' => 'required|string',
            'cc' => 'required|string',
            'grasa_viceral' => 'required|string',
            'evolucion' => 'required|string',
            'cliente_id' => 'required|string',
        ];

        $messages = [
            'peso.required' => 'El peso es requerido',
            'peso.string' => 'El peso debe ser un número',
            'musculo.required' => 'El porcentaje de musculo es requerido',
            'musculo.string' => 'El porcentaje de musculo debe ser un número',
            'grasas.required' => 'El porcentaje de grasas es requerido',
            'grasas.string' => 'El porcentaje de grasas debe ser un número',
            'porcentaje_grasa.required' => 'El porcentaje de grasa es requerido',
            'porcentaje_grasa.string' => 'El porcentaje de grasa debe ser un número',
            'cc.required' => 'El CC es requerido',
            'cc.string' => 'El CC debe ser un número',
            'grasa_viceral.required' => 'El porcentaje de grasa visceral es requerido',
            'grasa_viceral.string' => 'El porcentaje de grasa visceral debe ser un número',
            'evolucion.required' => 'La evolución es requerida',
            'evolucion.string' => 'La evolución debe ser un texto',
            'cliente_id.required' => 'El cliente es requerido',
            'cliente_id.string' => 'El cliente debe ser un número'
        ];

        $this->validate($request, $rules, $messages);

        $cliente = User::find($request->cliente_id);
        if ($cliente == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El cliente no existe',
                'data' => null,
            ]);
        }

        $nutricionista = User::find($cliente->nutricionist_id);
        if ($nutricionista == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El nutricionista no existe',
                'data' => null,
            ]);
        }

        if($cliente->suscripcion == null) {
            //En caso de no tener un suscripcion activa
            //Se procede a crear una suscripcion
            $suscripcion = new Suscription();
            $suscripcion->start_date = date('Y-m-d');
            $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
            $suscripcion->user_id = $cliente->id;
            $suscripcion->save();
        } else {
            if (date('Y-m-d') > $cliente->suscripcion->end_date) {
                //En caso de tener una suscripcion pero esta haya expirado
                //Se procede a actualizar la fecha de expiracion
                $cliente->suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
                $cliente->suscripcion->save();
            }
        }


        //$cita->fecha_cita = now();
        $cita->weight = (double) $request->peso;
        $cita->muscle = (double) $request->musculo;
        $cita->fat = (double) $request->grasas;
        $cita->average_fat = (double) $request->porcentaje_grasa;
        $cita->cc = (double) $request->cc;
        $cita->viseral_fat = (double) $request->grasa_viceral;
        $cita->evolution = $request->evolucion;
        $cita->client_id = $cliente->id;
        $cita->nutricionist_id = $nutricionista->id;
        $cita->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita actualizada correctamente',
            'data' => [
                'cita' => $cita,
                'cliente' => $cliente,
                'suscripcion' => $cliente->suscripcion,
                'nutricionista' => $nutricionista,
            ]
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/eliminar/cita-control/{cita}",
     *     summary="Delete cita de control",
     *     operationId="deleteCitaControl",
     *     tags={"cita-control"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="cita",
     *         in="path",
     *         description="Id of the cita control",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cita control deleted succesfully"),
     *     @OA\Response(response=404, description="Cita control not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function deleteCita(Appoinment $cita)
    {
        $equivalencia = NutritionEquivalent::where('appointment_id', $cita->id)->first();

        if ($equivalencia != null) {
            $equivalencia->delete();
        }

        $cita->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita de control borrada exitosamente',
            'data' => null
        ]);
    }
}
