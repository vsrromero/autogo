<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'address', 'city', 'county', 'postcode'];

    public function rules($id = null) : array
    {
        return [
            'name' => 'required|string|max:30',
            'email' => 'required|unique:customers,email,'.$id.'|string|max:100',
            'phone' => 'string|max:20',
            'address' => 'string|max:500',
            'city' => 'string|max:50',
            'county' => 'string|max:50',
            'postcode' => 'string|max:10',
        ];
    }
}
