<?php

namespace App\Http\Controllers\API\Appoinment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Subcategory;
use App\Models\User;

class RecordsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show/history-cita-control/{patience_id}",
     *     summary="Mostrando historial de citas del paciente",
     *     operationId="showAllCitaControlPatience",
     *     tags={"cita-control"},
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
        $records = Record::where('client_id', $user->id)->get();
        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando citas de control del paciente',
            'data' => $records
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/cita-control/{patience_id}/{cita_control_id}",
     *     summary="Mostrando cita de control del cliente",
     *     operationId="showCitaControlPatience",
     *     tags={"cita-control"},
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
    public function show(User $user, Record $record)
    {
        if ($record->client_id == $user->id) {
            //Means the record requested belongs to the user we just passed by the request
            return response()->json([
                'code' => 200,
                'msg' => 'Mostrando la cita control que solicitaste',
                'data' => $record
            ]);
        }
    }
  
    /**
     * @OA\Post(
     *     path="/api/create/cita-control",
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
     *                  property="fecha",
     *                  type="date"
     *              ),
     *              @OA\Property(
     *                  property="peso",
     *                  type="float"
     *              ),
     *              @OA\Property(
     *                  property="musculo",
     *                  type="float"
     *              ),
     *              @OA\Property(
     *                  property="grasa",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="porcentaje_grasa",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="cc",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="ejercicio",
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
            'fecha' => 'required',
            'peso' => 'required',
            'musculo' => 'required',
            'grasa' => 'required',
            'porcentaje_grasa' => 'required',
            'cc' => 'required',
            'ejercicio' => 'required'
        ];

        $messages = [
            'fecha.required' => 'Es necesarion indicar una fecha',
            'peso.required' => 'Es necesarion indicar el peso',
            'musculo.required' => 'Es necesarion indicar el musculo',
            'grasa.required' => 'Es necesarion indicar la grasa',
            'porcentaje_grasa.required' => 'Es necesarion indicar el porcentaje de grasa',
            'cc.required' => 'Es necesarion indicar el cc',
            'ejercicio.required' => 'Es necesarion indicar el ejercicio recomendado',
        ];

        $this->validate($request, $rules, $messages);

        $client_id = (int) $request->cliente_id;
        $user = User::find($client_id);
        if ($user == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El paciente no existe',
                'data' => null,
            ]);
        }

        $record = new Record();
        $record->date = ($request->filled('fecha')) ? $request->fecha : now();
        $record->weight = ($request->filled('peso')) ? $request->peso : null;
        $record->muscle = ($request->filled('musculo')) ? $request->musculo : null;
        $record->fat = ($request->filled('grasa')) ? $request->grasa : null;
        $record->average_fat = ($request->filled('porcentaje_grasa')) ? $request->porcentaje_grasa : null;
        $record->cc = ($request->filled('cc')) ? $request->cc : null;
        $record->excercise = ($request->filled('ejercicio')) ? $request->ejercicio : null;
        $record->viseral_fat = ($request->filled('grasa_viseral')) ? $request->grasa_viseral : null;
        $record->notes_client = ($request->filled('notas_cliente')) ? $request->notas_cliente : null;
        $record->notes_intern = ($request->filled('notas_internas')) ? $request->notas_internas : null;
        $record->height = ($request->filled('estatura')) ? $request->estatura : 0.0;
        $subcategory = Subcategory::find($request->consumo_agua_id);
        if ($subcategory == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'La consumo de agua Id no existe',
                'data' => null,
            ]);
        }
        $record->water_consumption_id = $subcategory->id;
        $record->client_id = $user->id;
        $record->save();

        return response()->json([
            'code' => 200,
            'msg' => 'La cita de control del paciente ' . $user->name . ' se creo exitosamente',
            'data' => $record
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'fecha' => 'required',
            'peso' => 'required',
            'musculo' => 'required',
            'grasa' => 'required',
            'porcentaje_grasa' => 'required',
            'cc' => 'required',
            'ejercicio' => 'required'
        ];

        $messages = [
            'fecha.required' => 'Es necesarion indicar una fecha',
            'peso.required' => 'Es necesarion indicar el peso',
            'musculo.required' => 'Es necesarion indicar una fecha',
            'grasa.required' => 'Es necesarion indicar una fecha',
            'porcentaje_grasa.required' => 'Es necesarion indicar una fecha',
            'cc.required' => 'Es necesarion indicar una fecha',
            'ejercicio.required' => 'Es necesarion indicar una fecha',
        ];

        $this->validate($request, $rules, $messages);

        $record = Record::find($id);
        $user = User::find($request->cliente_id);
        if ($user == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'El paciente no existe',
                'data' => null,
            ]);
        }

        $record->date = ($request->filled('fecha')) ? $request->fecha : $record->date;
        $record->weight = ($request->filled('peso')) ? $request->peso : $record->weight;
        $record->muscle = ($request->filled('musculo')) ? $request->musculo : $record->muscle;
        $record->fat = ($request->filled('grasa')) ? $request->grasa : $record->fat;
        $record->average_fat = ($request->filled('porcentaje_grasa')) ? $request->porcentaje_grasa : $record->average_fat;
        $record->cc = ($request->filled('cc')) ? $request->cc : $record->cc;
        $record->excercise = ($request->filled('ejercicio')) ? $request->ejercicio : $record->excercise;
        $record->viseral_fat = ($request->filled('grasa_viseral')) ? $request->grasa_viseral : $record->viseral_fat;
        $record->notes_client = ($request->filled('notas_cliente')) ? $request->notas_cliente : $record->notes_client;
        $record->notes_intern = ($request->filled('notas_internas')) ? $request->notas_internas : $record->notes_intern;
        $record->height = ($request->filled('estatura')) ? $request->estatura : $record->height;
        $subcategory = Subcategory::find($request->consumo_agua_id);
        if ($subcategory == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'La consumo de agua Id no existe',
                'data' => null,
            ]);
        }
        $record->water_consumption_id = $subcategory->id;
        $record->client_id = $user->id;
        $record->save();

        return response()->json([
            'code' => 200,
            'msg' => 'La cita de control del paciente ' . $user->name . ' se actualizo exitosamente',
            'data' => $record
        ]);
    }

    public function delete(Record $record)
    {
        $record->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Cita de control eliminada',
            'data' => null
        ]);
    }
}
