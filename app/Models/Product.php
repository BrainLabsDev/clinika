<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_quantity',
        'nutritional_exchange',
        'additional_details',
        'subcategory_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subcategoria()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }
}
