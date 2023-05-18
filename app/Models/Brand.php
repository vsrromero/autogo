<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function rules($id = null)
    {
        return [
            'name' => 'required|unique:brands,name,'.$id.'|string|max:30',
            'image' => 'required|string|max:100',
        ];
    }

}
