<?php

namespace App\Http\Controllers\API\NutritionalEquivalent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NutritionEquivalent;
use App\Models\Appoinment;
use App\Models\Record;
use App\Models\User;

class NutritionalEquivalentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show/equivalencias-nutricionales/{citaControl}",
     *     summary="Obtener las equivalencias nutricionales de una cita de control",
     *     operationId="showEquivalenciasNutricionales",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="citaControl",
     *         in="path",
     *         description="Id of Cita de Control",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing equivalencias nutricionales of a Cita de Control"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function index(Appoinment $citaControl)
    {
        $equivalencias_nutricionales = NutritionEquivalent::where('appointment_id',$citaControl->id)->get();

        if ($equivalencias_nutricionales->isEmpty()) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se han encontrado equivalencias nutricionales para la cita de control con id: ' . $citaControl->id,
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Equivalencias nutricionales obtenidas correctamente',
            'data' => [
                'cita_control' => $citaControl,
                'equivalencias_nutricionales' => $equivalencias_nutricionales
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/equivalencia-nutricional/{equivalenciaNutricional}",
     *     summary="Obtener la equivalencia nutricional solicitada",
     *     operationId="showEquivalenciaNutricional",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="equivalenciaNutricional",
     *         in="path",
     *         description="Id of Equivalencia Nutricional",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing equivalencia nutricional requested"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(NutritionEquivalent $equivalenciaNutricional)
    {
        return response()->json([
            'code' => 200,
            'msg' => 'Equivalencia nutricional obtenida correctamente',
            'data' => $equivalenciaNutricional
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/equivalencia-nutricional",
     *     summary="Crear Equivalencia Nutricional",
     *     operationId="createEquivalenciaNutricional",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="desayuno",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="media_mañana",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="almuerzo",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="media_tarde",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="cena",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="merienda_noche",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="cita_control_id",
     *                  type="integer"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="EquivalenciaNutricional created"),
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
            'cita_control_id' => 'required|integer',
        ];

        $messages = [
            'cita_control_id.required' => 'El campo cita control id es obligatorio',
            'cita_control_id.integer' => 'El campo cita control id debe ser un número entero',
        ];

        $this->validate($request, $rules, $messages);

        $record = Record::findOrFail($request->cita_control_id);

        if ($record->equivalenciaNutricional != null){
            return response()->json([
                'code' => 422,
                'msg' => 'Ya existe una equivalencia nutricional para esta cita de control',
                'data' => null
            ]);
        }

        $equivalencia_nutricional = new NutritionEquivalent();
        $equivalencia_nutricional->breakfast = ($request->filled('desayuno')) ? json_encode($request->desayuno) : null;
        $equivalencia_nutricional->mid_lunch = ($request->filled('media_mañana')) ? json_encode($request->media_mañana) : null;
        $equivalencia_nutricional->lunch = ($request->filled('almuerzo')) ? json_encode($request->almuerzo) : null;
        $equivalencia_nutricional->mid_dinner = ($request->filled('media_tarde')) ? json_encode($request->media_tarde) : null;
        $equivalencia_nutricional->dinner = ($request->filled('cena')) ? json_encode($request->cena) : null;
        $equivalencia_nutricional->snack = ($request->filled('merienda_noche')) ? json_encode($request->merienda_noche) : null;
        $equivalencia_nutricional->record_id = $record->id;
        $equivalencia_nutricional->save();

        return response()->json([
            'code' => 201,
            'msg' => 'Equivalencia nutricional creada correctamente',
            'data' => $equivalencia_nutricional
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/equivalencia-nutricional/{EquivalenciaNutricional}",
     *     summary="Actualizar equivalencia nutricional",
     *     operationId="updateEquivalenciaNutricional",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="EquivalenciaNutricional",
     *         in="path",
     *         description="Id of Equivalencia Nutricional",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="desayuno",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="media_mañana",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="almuerzo",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *               @OA\Property(
     *                  property="media_tarde",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="cena",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="merienda_noche",
     *                  type="array",
     *                  @OA\Items(
     *                    @OA\Property(property="carbohidratos", type="string"),
     *                    @OA\Property(property="frutas", type="string"),
     *                    @OA\Property(property="vegetales", type="string"),
     *                    @OA\Property(property="lacteos", type="string"),
     *                    @OA\Property(property="proteinas", type="string"),
     *                    @OA\Property(property="grasas", type="string", format="byte"),
     *                  ),
     *               ),
     *              @OA\Property(
     *                  property="cita_control_id",
     *                  type="integer"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Consultorio created"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function update(Request $request, NutritionEquivalent $equivalenciaNutricional)
    {
        $rules = [
            'cita_control_id' => 'required|integer',
        ];

        $messages = [
            'cita_control_id.required' => 'El campo cita control id es obligatorio',
            'cita_control_id.integer' => 'El campo cita control id debe ser un número entero',
        ];

        $this->validate($request, $rules, $messages);

        $equivalenciaNutricional->breakfast = ($request->filled('desayuno')) ? json_encode($request->desayuno) : null;
        $equivalenciaNutricional->mid_lunch = ($request->filled('media_mañana')) ? json_encode($request->media_mañana) : null;
        $equivalenciaNutricional->lunch = ($request->filled('almuerzo')) ? json_encode($request->almuerzo) : null;
        $equivalenciaNutricional->mid_dinner = ($request->filled('media_tarde')) ? json_encode($request->media_tarde) : null;
        $equivalenciaNutricional->dinner = ($request->filled('cena')) ? json_encode($request->cena) : null;
        $equivalenciaNutricional->snack = ($request->filled('merienda_noche')) ? json_encode($request->merienda_noche) : null;

        $citaControl = Appoinment::findOrFail($request->cita_control_id);
        $equivalenciaNutricional->appointment_id = $citaControl->id;
        $equivalenciaNutricional->update();

        return response()->json([
            'code' => 200,
            'msg' => 'Equivalencia nutricional actualizada correctamente',
            'data' => $equivalenciaNutricional
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show-last/equivalencia-nutricional/{user}",
     *     summary="Obtener las equivalencias nutricionales de una cita de control",
     *     operationId="showLastEquivalenciaNutricional",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of the User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing equivalencias nutricionales of a Cita de Control requested"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function showLast(User $user)
    {
         $cita = Appoinment::where('client_id', $user->id)->orderByDesc('date')->first();

         if ($cita == null) {
             return response()->json([
                 'code' => 404,
                 'msg' => 'No se tiene registrado una cita de control para este cliente',
                 'data' => null
             ]);
         }

        $equivalenciaNutricional = NutritionEquivalent::where('appointment_id', $cita->id)->first();

        if ($equivalenciaNutricional == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se tiene registrado una equivalencia nutricional para esta cita de control',
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Equivalencia nutricional encontrada',
            'data' => $equivalenciaNutricional
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/eliminar/equivalencia/{equivalenciaNutricional}",
     *     summary="Delete equivalencia nutricional",
     *     operationId="deleteEquivalenciaNutricional",
     *     tags={"equivalencia-nutricional"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="equivalenciaNutricional",
     *         in="path",
     *         description="Id of the Equivalencia Nutricional",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Equivalencia Nutricional deleted succesfully"),
     *     @OA\Response(response=404, description="Equivalencia Nutrcional not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function deleteEquivalencia(NutritionEquivalent $equivalenciaNutricional)
    {
        $equivalenciaNutricional->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Equivalencia nutricional eliminada',
            'data' => null
        ]);
    }
}
