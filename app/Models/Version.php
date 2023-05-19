<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

class Version extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id', 'name', 'image', 'number_of_doors', 'seats', 'airbags','abs'];

    public function rules($id = null) : array
    {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:versions,name,'.$id.'|string|max:30',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'number_of_doors' => 'required|integer|min:3|max:5',
            'seats' => 'required|integer|min:4|max:10',
            'airbags' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

}