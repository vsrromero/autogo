<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['version_id', 'reg', 'available', 'ml'];

    public function rules($id = null) : array
    {
        return [
            'version_id' => 'exists:versions,id',
            'reg' => 'required',
            'available' => 'required',
            'ml' => 'required',
        ];
    }

}
