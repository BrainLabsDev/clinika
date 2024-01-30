<?php

namespace App\Http\Controllers\API\PhysicalActivity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\PhysicalActivity;

class PhysicalActivityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show/actividades-fisicas",
     *     summary="Mostrando las actividaddes fisicas disponibles",
     *     operationId="showActividadesFisicas",
     *     tags={"ActividadFisica"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing aviable Actividades Fisicas"),
     *     @OA\Response(response=403, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function index()
    {
        $code = 404;
        $msg = 'No se encontraron actividades fisicas';
        $data = null;

        $actividadFisica = PhysicalActivity::all();
        if ($actividadFisica != null) {
            $code = 200;
            $msg = 'Mostrando todas las actividades fisicas';
            $data = $actividadFisica;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/show/lobezno/{actividadFisica}",
     *     summary="Mostrando la actividad fisica",
     *     operationId="showActividadFisica",
     *     tags={"ActividadFisica"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="actividadFisica",
     *         in="path",
     *         description="Id of the Actividad Fisica",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing specific Actividad Fisica"),
     *     @OA\Response(response=403, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function show(PhysicalActivity $actividadFisica)
    {
        $code = 404;
        $msg = 'No se encontro la actividad fisica';
        $data = null;

        if ($actividadFisica != null) {
            $code = 200;
            $msg = 'Mostrando actividad fisica';
            $data = $actividadFisica;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/create/actividad-fisica",
     *     summary="Crear Actividad Fisica",
     *     operationId="createActividadFisica",
     *     tags={"ActividadFisica"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="descripcion",
     *                  type="string"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Actividad Fisica created successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
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
            'nombre' => 'required|string',
            'descripcion' => 'required|string'
        ];

        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser un string',
            'descripcion.required' => 'La descripcion es requerida',
            'descripcion.string' => 'La descripcion debe ser un string'
        ];

        $this->validate($request, $rules, $messages);

        $actividadFisica = new PhysicalActivity();
        $actividadFisica->name = $request->nombre;
        $actividadFisica->description = $request->descripcion;
        $actividadFisica->sku = Str::slug($request->nombre, '-');
        $actividadFisica->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Actividad fisica creada correctamente',
            'data' => $actividadFisica
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/update/actividad-fisica/{actividadFisica}",
     *     summary="Actualizar Actividad Fisica",
     *     operationId="updateActividadFisica",
     *     tags={"ActividadFisica"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="actividadFisica",
     *         in="path",
     *         description="Id of the Actividad Fisica",
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
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="descripcion",
     *                  type="string"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Actividad Fisica updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function actualizar(Request $request, PhysicalActivity $actividadFisica)
    {
         $rules = [
            'nombre' => 'required|string',
            'descripcion' => 'required|string'
        ];

        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser un string',
            'descripcion.required' => 'La descripcion es requerida',
            'descripcion.string' => 'La descripcion debe ser un string'
        ];

        $this->validate($request, $rules, $messages);

        $actividadFisica->nombre = $request->nombre;
        $actividadFisica->descripcion = $request->descripcion;
        $actividadFisica->sku = Str::slug($request->nombre, '-');
        $actividadFisica->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Actividad fisica actualizada correctamente',
            'data' => $actividadFisica
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/delete/actividad-fisica/{actividadFisica}",
     *     summary="Eliminando la actividad fisica",
     *     operationId="deleteActividadFisica",
     *     tags={"ActividadFisica"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="actividadFisica",
     *         in="path",
     *         description="Id of the Actividad Fisica",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Actividad Fisica deleted successfully"),
     *     @OA\Response(response=403, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function delete(PhysicalActivity $actividadFisica)
    {
        //$actividadFisica->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Actividad fisica eliminada correctamente',
            'data' => $actividadFisica
        ],200);
    }
}
