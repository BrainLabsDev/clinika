<?php

namespace App\Http\Controllers\API\Medical;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class MedicalConditionsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/show-categories",
     *     summary="Obtener nuevas condiciones medicas",
     *     operationId="showMedicalConditions",
     *     tags={"medical-conditions"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing medical conditions"),
     *     @OA\Response(response=404, description="No medical conditions found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function index()
    {
        $categorias = [];
        $categories = Category::all();
        foreach ($categories as $category) {
            $data = [
                $category->name => []
            ];

            $categorias = array_merge($data,(array) $categorias);

            foreach ($category->subcategorias as $subcategory) {
                $categorias[$category->name][$subcategory->id] = $subcategory->description;
            }  
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando condiciones medicas',
            'data' => $categorias
        ]);
    }
}
