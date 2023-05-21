<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'car_id',
        'starting_date',
        'planned_return_date',
        'end_date',
        'value',
        'initial_ml',
        'final_ml',
    ];

    public function rules($id = null) : array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
            'starting_date' => 'required|date',
            'planned_return_date' => 'required|date',
            'end_date' => 'date',
            'value' => 'required|numeric',
            'initial_ml' => 'required|integer',
            'final_ml' => 'integer',
        ];
    }
}
