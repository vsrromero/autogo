<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request): JsonResponse
    {
        
        $brand_repository = new BrandRepository($this->brand);

        if($request->has('fields_versions')) {
            $fields_versions = 'versions:id,'.$request->fields_versions;
            $brand_repository->selectFieldsRelatedRegisters($fields_versions);
        } else {
            $brand_repository->selectFieldsRelatedRegisters('versions');
        }

        if($request->has('filter')) {
            $brand_repository->filter($request->filter);
        }

        if($request->has('fields')) {
            $brand_repository->selectFields($request->fields);
        } 

        return response()->json($brand_repository->getResponse(), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
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

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        
        // Get a single brand
        $brand = $this->brand->with('versions')->find($id);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Update a brand
        $brand = $this->brand->find($id);
        if (is_null($brand)) {
            return response()->json(['error' => "Unable to update - Brand with id $id does not exist"], 404);
        }

        $dinamicRules = [];

        // remove the old image from storage
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Delete a brand
        $brand = $this->brand->find($id);
        if (is_null($brand)) {
            return response()->json(['error' => "Unable to delete - Brand with id $id does not exists"], 404);
        }
        // Delete the image from storage
        $version = $this->brand->find($id);
        Storage::disk('public')->delete($version->image);
        // Delete the brand
        $brand->delete();
        return response()->json(null, 204);
    }
}
