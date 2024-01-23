<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $categoria = Category::where('name', $row[0])->first();
        if ($categoria) {
            return null;
        } else {
            if($row[0] != 'Categoria' && $row[0] != null) {
                return new Category([
                    'name' => trim($row[0]),
                    'icon' => null
                ]);
            }
        }
    }
}
