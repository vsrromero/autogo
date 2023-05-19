<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand){
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all brands
        $brands = $this->brand->all();
        return response($brands, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate($this->brand->rules());

        $image = $request->file('image');
        $image_path = $image->store('images', 'public');

        // Create a new brand
        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_path
        ]);

        /* 
        //Another way to create a new brand
        $brand->name = $request->name;
        $brand->image = $image_path;
        $brand->save();
        */

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get a single brand
        $brand = $this->brand->find($id);
        if (is_null($brand)) {
            return response()->json(['error' => "Brand with id $id does not exist"], 404);
        }
        return response()->json($brand, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Update a brand
        $brand = $this->brand->find($id);
        if (is_null($brand)) {
            return response()->json(['error' => "Unable to update - Brand with id $id does not exist"], 404);
        }
    
        $dinamicRules = [];

        if ($request->hasFile('image')) {
            // Delete the previous image from storage
            Storage::disk('public')->delete($brand->image);
        }
    
        // dinamic validation
        foreach ($brand->rules($id) as $input => $rule) {
            if (array_key_exists($input, $request->all())) {
                $dinamicRules[$input] = $rule;
            }
        }
    
        // validation
        $request->validate($dinamicRules);
    
        // Update the fields based on the request data
        if ($request->method() === 'PATCH') {
            // Update only the specified fields
            $brand->fill($request->only(array_keys($dinamicRules)));
        } else {
            // Update all fields for PUT requests
            $brand->fill($request->all());
        }
    
        // Update the 'image' field if it exists in the request data
        if ($request->hasFile('image')) {

            $request->validate(['image' => $this->brand->rules($id)['image']]);
    
    
            $image = $request->file('image');
            $image_path = $image->store('images', 'public');
            $brand->image = $image_path;
        }
    
        $brand->save();
    
        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete a brand
        $brand = $this->brand->find($id);
        if (is_null($brand)) {
            return response()->json(['error' => "Unable to delete - Brand with id $id does not exists"], 404);
        }
        $brand->delete();
        return response()->json(null, 204);
    }
}
