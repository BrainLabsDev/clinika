<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appoinment extends Model
{
    use HasFactory;
    protected $table = "appointments";

    public function equivalenciaNutricional()
    {
        return $this->hasOne(NutritionEquivalent::class);
    }
}
