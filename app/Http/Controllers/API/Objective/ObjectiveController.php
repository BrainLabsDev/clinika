<?php

namespace App\Http\Controllers\API\Objective;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Objective;

class ObjectiveController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/show/objetivos",
     *     summary="Mostrando los objetivos disponibles",
     *     operationId="showObjetivos",
     *     tags={"Objetivos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing aviable Objetivos"),
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
        $msg = 'No se encontraron objetivos';
        $data = null;

        $objetivo = Objective::all();
        if ($objetivo != null) {
            $code = 200;
            $msg = 'Mostrando todos los objetivos';
            $data = $objetivo;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/show/objetivo/{objetivo}",
     *     summary="Mostrando el objetivo",
     *     operationId="showObjetivo",
     *     tags={"Objetivos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="objetivo",
     *         in="path",
     *         description="Id of the Objetivo",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing specific Objetivo"),
     *     @OA\Response(response=403, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function show(Objective $objetivo)
    {
        $code = 404;
        $msg = 'No se encontro el objetivo';
        $data = null;

        if ($objetivo != null) {
            $code = 200;
            $msg = 'Mostrando el objetivo';
            $data = $objetivo;
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/create/objetivo",
     *     summary="Crear objetivo",
     *     operationId="createObjetivo",
     *     tags={"Objetivos"},
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
     *     @OA\Response(response=200, description="Objetivo created successfully"),
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

        $objetivo = new Objective();
        $objetivo->nombre = $request->nombre;
        $objetivo->descripcion = $request->descripcion;
        $objetivo->sku = Str::slug($request->nombre, '-');
        $objetivo->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Objetivo creado correctamente',
            'data' => $objetivo
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/update/objetivo/{objetivo}",
     *     summary="Actualizar Objetivo",
     *     operationId="updateObjetivo",
     *     tags={"Objetivos"},
     *     @OA\Parameter(
     *         name="objetivo",
     *         in="path",
     *         description="Id of the Objetivo",
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
     *     @OA\Response(response=200, description="Objetivo updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function update(Request $request, Objective $objetivo)
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

        $objetivo->nombre = $request->nombre;
        $objetivo->descripcion = $request->descripcion;
        $objetivo->sku = Str::slug($request->nombre, '-');
        $objetivo->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Objetivo actualizado correctamente',
            'data' => $objetivo
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/delete/objetivo/{objetivo}",
     *     summary="Eliminando el objetivo",
     *     operationId="deleteObjetivo",
     *     tags={"Objetivos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="objetivo",
     *         in="path",
     *         description="Id of the Objetivo",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Objetivo deleted successfully"),
     *     @OA\Response(response=403, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function delete(Objective $objetivo)
    {
        $objetivo->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Objetivo eliminado correctamente',
            'data' => $objetivo
        ],200);
    }
}
