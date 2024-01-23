<?php

namespace App\Http\Controllers\API\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Excel Classes
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/import/categorias",
     *     summary="Importar Categorias del excel",
     *     operationId="importCategorias",
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
     *     @OA\Response(response=200, description="Cateogries imported"),
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
        Excel::import(new CategoryImport, $file);
        $data = Category::all();
        return response()->json([
            'code' => 200,
            'msg' => 'Categorias importadas correctamente',
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/categorias",
     *     summary="Mostrando todas las categorias",
     *     operationId="showCategorias",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing all categories"),
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
        $categorias = Category::all();
        if ($categorias->isEmpty()) {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay categorias registradas',
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando categorias',
            'data' => $categorias
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/categoria/{categoria}",
     *     summary="Mostrando la categoria solicitada",
     *     operationId="showCategoria",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="categoria",
     *         in="path",
     *         description="Id of Categoria",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing all categories"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(Category $categoria)
    {
        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando la categoria solicitada',
            'data' => $categoria
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/categoria/{categoria}/subcategorias",
     *     summary="Mostrando las subcategorias de la categoria solicitada",
     *     operationId="showCategoriaSubcategorias",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="categoria",
     *         in="path",
     *         description="Id of Categoria",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing all subcategories of category"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function showSubcategorias(Category $categoria)
    {
        if ($categoria->subcategorias == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay subcategorias registradas en la categoria ' . $categoria->nombre,
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando subcategorias que pertenecen a la categoria ' . $categoria->nombre,
            'data' => [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'icono' => $categoria->icono,
                'subcategorias' => $categoria->subcategorias
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/categoria/{categoria}/productos",
     *     summary="Mostrando los productos de la categoria solicitada",
     *     operationId="showCategoriaProductos",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="categoria",
     *         in="path",
     *         description="Id of Categoria",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing all products of category"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function showProductos(Category $categoria)
    {
        if ($categoria->productos == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay productos registrados en la categoria ' . $categoria->nombre,
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando productos de la categoria ' . $categoria->nombre,
            'data' => [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'icono' => $categoria->icono,
                'productos' => $categoria->productos
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/categoria",
     *     summary="Crear categoria",
     *     operationId="createCategoria",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Category created"),
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
            'nombre' => 'required|string'
        ];

        $messages = [
            'nombre.required' => 'El nombre de la categoria es requerido',
            'nombre.string' => 'El nombre de la categoria debe ser un texto'
        ];

        $this->validate($request, $rules, $messages);

        $categoria = new Category();
        $categoria->nombre = $request->nombre;
        $categoria->icono = ($request->filled('icono')) ? $request->icono : null;
        $categoria->save();

        return response()->json([
            'code' => 201,
            'msg' => 'Categoria creada correctamente',
            'data' => $categoria
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/categoria/{categoria}",
     *     summary="Actualizar categoria",
     *     operationId="updateCategoria",
     *     tags={"categoria-alimentos"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="categoria",
     *         in="path",
     *         description="Id of Categoria",
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
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Category updated"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function update(Request $request, Category $categoria)
    {
        $rules = [
            'nombre' => 'required|string'
        ];

        $messages = [
            'nombre.required' => 'El nombre de la categoria es requerido',
            'nombre.string' => 'El nombre de la categoria debe ser un texto'
        ];

        $this->validate($request, $rules, $messages);

        $categoria->nombre = $request->nombre;
        $categoria->icono = ($request->filled('icono')) ? $request->icono : null;
        $categoria->update();

        return response()->json([
            'code' => 200,
            'msg' => 'Categoria actualizada correctamente',
            'data' => $categoria
        ]);
    }
}
