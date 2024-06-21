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
     *     path="/api/show-history/cita/{patience_id}",
     *     summary="Mostrando historial de citas del paciente",
     *     operationId="showAllCitaPatience",
     *     tags={"cita"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="patience_id",
     *         in="path",
     *         description="Id of patience",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing all apointments of the Patience"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="Patience not found"),
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
            $msg = 'Mostrando historial de citas del paciente';
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
     *     path="/api/show/cita/{patience_id}/{appointment_id}",
     *     summary="Mostrando cita del cliente",
     *     operationId="showCitaPatience",
     *     tags={"cita"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="patience_id",
     *         in="path",
     *         description="Id of the Patience",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="appointment_id",
     *         in="path",
     *         description="Id of the Appointment",
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
    public function show(User $user, $id)
    {
        $code = 200;
        $msg = 'Mostrando cita actual';
        $cita = Appoinment::where('client_id', $user->id)->where('id', $id)->first();

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $cita,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/all-citas",
     *     summary="Mostrando cita del cliente",
     *     operationId="showCitaPatience",
     *     tags={"cita"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="patience_id",
     *         in="path",
     *         description="Id of the Patience",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="appointment_id",
     *         in="path",
     *         description="Id of the Appointment",
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
    public function showAll($fecha_consulta = null)
    {
        $code = 200;
        $msg = 'Mostrando las citas';
        if ($fecha_consulta != null) {
            $citas = Appoinment::whereDate('date', $fecha_consulta)->get();
        } else {
            $citas = Appoinment::all();
        }
       

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $citas,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/cita",
     *     summary="Crear cita",
     *     operationId="createCita",
     *     tags={"cita"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *              @OA\Property(
     *                  property="fecha_consulta",
     *                  type="date"
     *              ),
     *              @OA\Property(
     *                  property="hora_start",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="hora_end",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="paciente_id",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="clinica_id",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="nutriologo_id",
     *                  type="integer"
     *              ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Cita created"),
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
            'fecha_consulta' => 'required',
            'hora_start' => 'required',
            'hora_end' => 'required',
            'paciente_id' => 'required',
            'clinica_id' => 'required',
            'nutriologo_id' => 'required',
        ];

        $messages = [
            'fecha_consulta.required' => 'La fecha de la cita es requerida',
            'hora_start.required' => 'La hora de inicio de la cita es requerido',
            'hora_end.required' => 'La hora de finalizacion de la cita es requerido',
            'paciente_id.required' => 'El identificador del paciente es requerido',
            'clinica_id.required' => 'El identificador del consultorio es requerido',
            'nutriologo_id.required' => 'El identificador de la nutriologa es requerido'
        ];

        $this->validate($request, $rules, $messages);

        $cliente = User::find($request->paciente_id);
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

        $nutricionista = User::find($request->nutriologo_id);
        if ($nutricionista == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El nutricionista no existe',
                'data' => null,
            ]);
        }

        $consultorio = Room::find($request->clinica_id);
        if ($consultorio == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El Consultorio no existe',
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
        $cita->date = ($request->filled('fecha_consulta')) ? $request->fecha_consulta : now();
        $cita->notes = ($request->filled('notas')) ? $request->notas : null;
        $cita->video_call_url = ($request->filled('videoconferencia')) ? $request->videoconferencia : null;
        $cita->start_time = ($request->filled('hora_start')) ? $request->hora_start : null;
        $cita->end_time = ($request->filled('hora_end')) ? $request->hora_end : null;
        $cita->google_calendar = ($request->filled('google_calendar')) ? $request->google_calendar : null;
        $cita->status = ($request->filled('estado_consulta')) ? $request->estado_consulta : 'No Confirmado';
        $cita->client_id = $cliente->id;
        $cita->nutricionist_id = $nutricionista->id;
        $cita->consultive_room_id = $consultorio->id;
        $cita->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita creada correctamente',
            'data' => [
                'cita' => $cita,
                'cliente' => $cliente,
                'clinica' => $consultorio,
                'nutricionista' => $nutricionista,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/cita/{cita}",
     *     summary="Actualizar cita",
     *     operationId="updateCita",
     *     tags={"cita"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="cita",
     *         in="path",
     *         description="Id of the Cita",
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
     *                  property="fecha_consulta",
     *                  type="date"
     *              ),
     *              @OA\Property(
     *                  property="hora_start",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="hora_end",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="paciente_id",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="clinica_id",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="nutriologo_id",
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
            'fecha_consulta' => 'required',
            'hora_start' => 'required',
            'hora_end' => 'required',
            'paciente_id' => 'required',
            'clinica_id' => 'required',
            'nutriologo_id' => 'required',
        ];

        $messages = [
            'fecha_consulta.required' => 'La fecha de la cita es requerida',
            'hora_start.required' => 'La hora de inicio de la cita es requerido',
            'hora_end.required' => 'La hora de finalizacion de la cita es requerido',
            'paciente_id.required' => 'El identificador del paciente es requerido',
            'clinica_id.required' => 'El identificador del consultorio es requerido',
            'nutriologo_id.required' => 'El identificador de la nutriologa es requerido'
        ];

        $this->validate($request, $rules, $messages);

        $cliente = User::find($request->paciente_id);
        if ($cliente == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El cliente no existe',
                'data' => null,
            ]);
        }

        $nutricionista = User::find($request->nutriologo_id);
        if ($nutricionista == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El nutricionista no existe',
                'data' => null,
            ]);
        }

        $consultorio = Room::find($request->clinica_id);
        if ($consultorio == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El consultorio no existe',
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


        $cita->date = ($request->filled('fecha_consulta')) ? $request->fecha_consulta : now();
        $cita->notes = ($request->filled('notas')) ? $request->notas : null;
        $cita->video_call_url = ($request->filled('videoconferencia')) ? $request->videoconferencia : null;
        $cita->start_time = ($request->filled('hora_start')) ? $request->hora_start : null;
        $cita->end_time = ($request->filled('hora_end')) ? $request->hora_end : null;
        $cita->google_calendar = ($request->filled('google_calendar')) ? $request->google_calendar : null;
        $cita->status = ($request->filled('estado_consulta')) ? $request->estado_consulta : 'No Confirmado';
        $cita->client_id = $cliente->id;
        $cita->nutricionist_id = $nutricionista->id;
        $cita->consultive_room_id = $consultorio->id;
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
     *     path="/api/eliminar/cita/{cita}",
     *     summary="Delete cita",
     *     operationId="deleteCita",
     *     tags={"cita"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="cita",
     *         in="path",
     *         description="Id of the cita",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cita deleted succesfully"),
     *     @OA\Response(response=404, description="Cita not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function delete(Appoinment $cita)
    {
        /*$equivalencia = NutritionEquivalent::where('appointment_id', $cita->id)->first();

        if ($equivalencia != null) {
            $equivalencia->delete();
        }*/

        $cita->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita borrada exitosamente',
            'data' => null
        ]);
    }
}
