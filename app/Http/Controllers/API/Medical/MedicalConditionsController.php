<?php

namespace App\Http\Controllers\API\Medical;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class MedicalConditionsController extends Controller
{
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
