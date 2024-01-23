<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//EXCEL
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/import/productos",
     *     summary="Importar Productos del excel",
     *     operationId="importProductos",
     *     tags={"importFile"},
     *     security={ {"sanctum": {} }},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="file_excel",
     *                      description="file_excel",
     *                      type="file"
     *                  ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(response=200, description="Products imported"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function import(Request $request)
    {
        $file = $request->file('file_excel');
        Excel::import(new ProductsImport, $file);
        $data = Product::all();
        return response()->json([
            'code' => 200,
            'msg' => 'Productos importados correctamente',
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/productos",
     *     summary="Mostrando todos los productos",
     *     operationId="showProductos",
     *     tags={"alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing al products"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="Product not registered yet"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function index()
    {
        $data = Product::all();
        if ($data != null) {
            return response()->json([
                'code' => 200,
                'msg' => 'Mostrando productos',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay productos',
                'data' => null
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/show/producto/{producto}",
     *     summary="Mostrando el producto solicitado",
     *     operationId="showProducto",
     *     tags={"alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="producto",
     *         in="path",
     *         description="Id of Producto",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing product requested"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="Product not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(Product $producto)
    {
        if ($producto != null) {
            return response()->json([
                'code' => 200,
                'msg' => 'Mostrando producto',
                'data' => [
                    'categoria_id' => $producto->subcategoria->categoria_id,
                    'categoria' => $producto->subcategoria->categoria->nombre,
                    'subcategoria_id' => $producto->subcategoria_id,
                    'subcategoria' => $producto->subcategoria->nombre,
                    'producto' => $producto
                ]
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'Producto no encontrado',
                'data' => null
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/create/producto",
     *     summary="Crear producto",
     *     operationId="createProducto",
     *     tags={"alimentos"},
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
     *                  property="cantidad_producto",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="intercambio_nutricional",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="detalles_adicionales",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="subcategoria_id",
     *                  type="integer"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Producto created"),
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
            'nombre' => 'required',
            'cantidad_producto' => 'required',
            'intercambio_nutricional' => 'required',
            'subcategoria_id' => 'required',
        ];

        $messages = [
            'nombre.required' => 'El nombre del producto es requerido',
            'cantidad_producto.required' => 'La cantidad del producto es requerida',
            'intercambio_nutricional.required' => 'El intercambio nutricional es requerido',
            'subcategoria_id.required' => 'La subcategoria es requerida',
        ];

        $this->validate($request, $rules, $messages);

        $producto = new Product();
        $producto->nombre = $request->nombre;
        $producto->cantidad_producto = $request->cantidad_producto;
        $producto->intercambio_nutricional = $request->intercambio_nutricional;
        $producto->detalles_adicionales = ($request->filled('detalles_adicionales')) ? $request->detalles_adicionales : null;
        $producto->subcategoria_id = $request->subcategoria_id;
        $producto->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Producto creado correctamente',
            'data' => $producto
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/producto/{producto}",
     *     summary="Actualziar producto",
     *     operationId="updateProducto",
     *     tags={"alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="producto",
     *         in="path",
     *         description="Id of Producto",
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
     *                  property="cantidad_producto",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="intercambio_nutricional",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="detalles_adicionales",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="subcategoria_id",
     *                  type="integer"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Producto updated"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function update(Request $request, Product $producto)
    {
        $rules = [
            'nombre' => 'required',
            'cantidad_producto' => 'required',
            'intercambio_nutricional' => 'required',
            'subcategoria_id' => 'required',
        ];

        $messages = [
            'nombre.required' => 'El nombre del producto es requerido',
            'cantidad_producto.required' => 'La cantidad del producto es requerida',
            'intercambio_nutricional.required' => 'El intercambio nutricional es requerido',
            'subcategoria_id.required' => 'La subcategoria es requerida',
        ];

        $this->validate($request, $rules, $messages);

        $producto->nombre = $request->nombre;
        $producto->cantidad_producto = $request->cantidad_producto;
        $producto->intercambio_nutricional = $request->intercambio_nutricional;
        $producto->detalles_adicionales = ($request->filled('detalles_adicionales')) ? $request->detalles_adicionales : null;
        $producto->subcategoria_id = $request->subcategoria_id;
        $producto->update();

        return response()->json([
            'code' => 200,
            'msg' => 'Producto actualizado correctamente',
            'data' => $producto
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/delete/producto/{producto}",
     *     summary="Delete producto",
     *     operationId="deleteProducto",
     *     tags={"alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="Id of product",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Add Clients to the Consultorio requested"),
     *     @OA\Response(response=403, description="User cant be assign to any Consultorio"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function delete(Product $producto) {
        $producto->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'Producto Eliminado correctamente',
            'data' => null
        ]);
    }
}
