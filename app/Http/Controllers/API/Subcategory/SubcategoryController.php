<?php

namespace App\Http\Controllers\API\Subcategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubcategoryImport;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/import/subcategorias",
     *     summary="Importar Sucategorias del excel",
     *     operationId="importSubcategorias",
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
     *     @OA\Response(response=200, description="Subcategories imported"),
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
        Excel::import(new SubcategoryImport, $file);
        $data = Subcategory::all();
        return response()->json([
            'code' => 200,
            'msg' => 'Subcategorias importadas correctamente',
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/subcategorias",
     *     summary="Mostrando subcategorias de Alimentos",
     *     operationId="showSubcategorias",
     *     tags={"subcategoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing subcategories of Alimentos"),
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
        $code = 500;
        $msg = 'Error al obtener las subcategorias';
        $data = null;

        $subcategorias = Subcategory::all();
        if ($subcategorias->isEmpty()) {
            $code = 404;
            $msg = 'No se encontraron subcategorias';
        } else {
            $code = 200;
            $msg = 'Subcategorias obtenidas correctamente';
            $data = $subcategorias;
        }
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/subcategoria/{subcategoria}",
     *     summary="Mostrando la Subcategoria de Alimentos con su respectiva Categoria",
     *     operationId="showSubcategory",
     *     tags={"subcategoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="subcategoria",
     *         in="path",
     *         description="Id of Subcategoria",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing subcategory of Alimentos with it respective Category"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(Subcategory $subcategoria)
    {
        $code = 500;
        $msg = 'Error al obtener la subcategoria';
        $data = null;

        if ($subcategoria) {
            $code = 200;
            $msg = 'Subcategoria obtenida correctamente';
            $data = $subcategoria;
        } else {
            $code = 404;
            $msg = 'No se encontró la subcategoria';
        }
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => [
                'subcategoria' => $data,
                'categoria' => $data->categoria
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/subcategoria",
     *     summary="Crear subcategoria",
     *     operationId="createSubcategoria",
     *     tags={"subcategoria-alimentos"},
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
     *                  property="categoria_id",
     *                  type="integer"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Subcategory created"),
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
            'categoria_id' => 'required|integer'
        ];

        $messages = [
            'nombre.required' => 'El nombre de la subcategoria es requerido',
            'nombre.string' => 'El nombre de la subcategoria debe ser un texto',
            'categoria_id.required' => 'El id de la categoria es requerido',
            'categoria_id.integer' => 'El id de la categoria debe ser un numero entero'
        ];

        $this->validate($request, $rules, $messages);

        $categoria = Category::find($request->categoria_id);
        if (!$categoria) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se encontró la categoria',
                'data' => null
            ]);
        } else {
            $subcategoria = Subcategory::create($request->all());
            return response()->json([
                'code' => 200,
                'msg' => 'Subcategoria creada correctamente',
                'data' => [
                    'subcategoria' => $subcategoria,
                    'categoria' => $subcategoria->categoria
                ]
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/update/subcategoria/{subcategoria}",
     *     summary="Actualizar subcategoria",
     *     operationId="updateSubcategoria",
     *     tags={"subcategoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="subcategoria",
     *         in="path",
     *         description="Id of Subcategoria",
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
     *                  property="categoria_id",
     *                  type="integer"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Subcategory updated"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function update(Request $request, Subcategory $subcategoria)
    {
        $rules = [
            'nombre' => 'required|string',
            'categoria_id' => 'required|integer'
        ];

        $messages = [
            'nombre.required' => 'El nombre de la subcategoria es requerido',
            'nombre.string' => 'El nombre de la subcategoria debe ser un texto',
            'categoria_id.required' => 'El id de la categoria es requerido',
            'categoria_id.integer' => 'El id de la categoria debe ser un numero entero'
        ];

        $this->validate($request, $rules, $messages);

        $categoria = Category::find($request->categoria_id);

        if (!$categoria) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se encontró la categoria',
                'data' => null
            ]);
        } else {
            $subcategoria->update($request->all());
            return response()->json([
                'code' => 200,
                'msg' => 'Subcategoria actualizada correctamente',
                'data' => [
                    'subcategoria' => $subcategoria,
                    'categoria' => $subcategoria->categoria
                ]
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/show/subcategoria/{subcategoria}/productos",
     *     summary="Mostrando productos de una subcategoria",
     *     operationId="showSubcategoryProducts",
     *     tags={"subcategoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="subcategoria",
     *         in="path",
     *         description="Id of Subcategoria",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing products of a subcategory"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function showProductos(Subcategory $subcategoria)
    {
        $code = 500;
        $msg = 'Error al obtener los productos de la subcategoria';
        $data = null;

        if ($subcategoria) {
            $code = 200;
            $msg = 'Productos obtenidos correctamente';
            $data = [
                'categoria_id' => $subcategoria->id,
                'categoria' => $subcategoria->categoria->nombre,
                'subcategoria_id' => $subcategoria->id,
                'subcategoria' => $subcategoria->nombre,
                'productos' => $subcategoria->productos
            ];
        } else {
            $code = 404;
            $msg = 'No se encontró la subcategoria';
        }
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}
