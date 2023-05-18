<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $image_path = $image->store('images/brands', 'public');

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
            return response()->json(['error' => "Unable to update - Brand with id $id does not exists"], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = [];
            // dinamic validation

            foreach ($brand->rules($id) as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }

            // validation
            $request->validate($dinamicRules);
        } else {
            $request->validate($this->brand->rules($id));
        }


        $brand->update($request->all());
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
