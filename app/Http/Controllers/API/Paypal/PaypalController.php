<?php

namespace App\Http\Controllers\API\Paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Paypal;

class PaypalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paypal/catalogo",
     *     summary="Mostrando los catalogos de paypal disponibles",
     *     operationId="showCatalogos",
     *     tags={"paypal-catalogos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing options of payment for Paypal"),
     *     @OA\Response(response=401, description="User not authenticated"),
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
        $data = null;

        $catalogo_paypal = Paypal::all();
        if ($catalogo_paypal != null) {
            $data = $catalogo_paypal;
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando las opciones de pago en Paypal',
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/paypal/catalogo/{id}",
     *     summary="Mostrando los catalogos de paypal disponibles",
     *     operationId="showCatalogo",
     *     tags={"paypal-catalogos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of option Paypal",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing option of payment for Paypal"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show($id)
    {
        $data = null;
        $paypal = Paypal::find($id);

        if ($paypal != null) {
            $data = $paypal;
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando la opcion de pago solicitada',
            'data' => $data
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/paypal/catalogo",
     *     summary="Mostrando los catalogos de paypal disponibles",
     *     operationId="storeCatalogo",
     *     tags={"paypal-catalogos"},
     *     security={ {"sanctum": {} }},
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
     *                  property="precio",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="descuento",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="periodo",
     *                  type="integer"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Storing option of payment for Paypal"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
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
            'periodo' => 'required|numeric',
            'precio' => 'required|numeric'
        ];

        $messages = [
            'nombre.required' => 'El nombre es un campo requerido',
            'nombre.string' => 'El nombre debe ser de tipo texto',
            'periodo.required' => 'El periodo es un campo requerido',
            'periodo.numeric' => 'El periodo debe ser de tipo numerico',
            'precio.required' => 'El precio es un campo requerido',
            'precio.numeric' => 'El periodo debe ser de tipo numerico',
        ];

        $this->validate($request, $rules, $messages);

        $paypal = new Paypal();
        $paypal->sku = Str::slug($request->nombre, '-');
        $paypal->nombre = $request->nombre;
        $paypal->representacion_numerica = $request->periodo;
        $paypal->precio = (double) $request->precio;
        if ($request->filled('descuento')) {
            $paypal->descuento = (double) $request->descuento;
        }
        $paypal->save();

        return response()->json([
            'code' => 200,
            'msg' => 'La opcion de pago se ha creado exitosamente',
            'data' => $paypal
        ]);

    }

    /**
     * @OA\Delete(
     *     path="/api/paypal/catalogo/{id}",
     *     summary="Eliminando catalogo de paypal",
     *     operationId="deteleCatalogo",
     *     tags={"paypal-catalogos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of option Paypal",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing options of payment for Paypal"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function delete($id)
    {
        $data = null;
        $paypal = Paypal::find($id);

        if ($paypal != null) {
            $paypal->delete();
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Opcion de Paypal eliminada',
            'data' => $data
        ]);
    }
}
