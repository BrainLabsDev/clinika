<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Subcategory;
use Maatwebsite\Excel\Concerns\ToModel;

class SubcategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $subcategoria = Subcategory::where('name', $row[1])->first();
        if ($subcategoria) {
            return null;
        } else {
            if($row[1] != 'Subcategoria' && $row[1] != null) {
                $categoria = Category::where('name', $row[0])->first();
                if ($categoria != null) {
                    return new Subcategory([
                        'name' => trim($row[1]),
                        'category_id' => $categoria->id
                    ]);
                } else {
                    return null;
                }
            }
        }
    }
}
