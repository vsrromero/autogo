<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Version;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image'];

    public function rules($id = null) : array
    {
        return [
            'name' => 'required|unique:brands,name,'.$id.'|string|max:30',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function versions()
    {
        return $this->hasMany(Version::class);
    }

}
