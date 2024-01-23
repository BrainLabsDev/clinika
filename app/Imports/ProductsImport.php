<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Subcategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $producto = Product::where('name', $row[2])->first();
        if ($producto) {
            return null;
        } else {
            if($row[2] != 'Nombre del Alimento' && $row[2] != null) {
                $subcategoria = Subcategory::where('name', $row[1])->first();
                if ($subcategoria != null) {
                    if (!str_contains($row[2],'Total')) {
                        $string = Str::of($row[3])->explode('=');
                        $details = null;
                        if ($row[4] != '-' || $row[4] != null) {
                            $details = trim($row[4]);
                        }
                        return new Product([
                            'name' => trim($row[2]),
                            'product_quantity' => trim($string[0]),
                            'nutritional_exchange' => (isset($string[1])) ? trim($string[1]) : null,
                            'aditional_details' => $details,
                            'subcategory_id' => $subcategoria->id
                        ]);
                    }
                } else {
                    return null;
                }
            }
        }
    }
}
