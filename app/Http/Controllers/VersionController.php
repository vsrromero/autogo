<?php

namespace App\Http\Controllers;

use App\Models\Version;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class VersionController extends Controller
{
    private $version;
    public function __construct(Version $version)
    {
        $this->version = $version;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : JsonResponse
    {
        // Get all car versions
        $versions = $this->version->with('brand')->get();
        return response()->json($versions, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        // Validate request
        $request->validate($this->version->rules());
        
        $image = $request->file('image');
        $image_path = $image->store('images', 'public');

        // Create a new car version
        $version = $this->version->create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $image_path,
            'number_of_doors' => $request->number_of_doors,
            'seats' => $request->seats,
            'airbags' => $request->airbags,
            'abs' => $request->abs
        ]);

        return response()->json($version, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) : JsonResponse
    {
        // Get a single car version
        $version = $this->version->with('brand')->find($id);
        if (is_null($version)) {
            return response()->json(['error' => "Version with id $id does not exist"], 404);
        }
        return response()->json($version, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // Update a version
        $version = $this->version->find($id);
        if (is_null($version)) {
            return response()->json(['error' => "Unable to update - Version with id $id does not exist"], 404);
        }
    
        $dinamicRules = [];

        // remove the old image from storage
        if ($request->hasFile('image')) {
            // Delete the previous image from storage
            Storage::disk('public')->delete($version->image);
        }
    
        // dinamic validation
        foreach ($version->rules($id) as $input => $rule) {
            if (array_key_exists($input, $request->all())) {
                $dinamicRules[$input] = $rule;
            }
        }
    
        // validation
        $request->validate($dinamicRules);
    
        // Update the fields based on the request data
        if ($request->method() === 'PATCH') {
            // Update only the specified fields
            $version->fill($request->only(array_keys($dinamicRules)));
        } else {
            // Update all fields for PUT requests
            $version->fill($request->all());
        }
    
        // Update the 'image' field if it exists in the request data
        if ($request->hasFile('image')) {

            $request->validate(['image' => $this->version->rules($id)['image']]);
    
            $image = $request->file('image');
            $image_path = $image->store('images', 'public');
            $version->image = $image_path;
        }
    
        $version->save();
    
        return response()->json($version, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,int $id) : JsonResponse
    {
        // Delete a car version
        $version = $this->version->find($id);
        if (is_null($version)) {
            return response()->json(['error' => "Unable to delete - Version with id $id does not exists"], 404);
        }
        // Delete the image from storage
        $version = $this->version->find($id);
        Storage::disk('public')->delete($version->image);
        // Delete the version
        $version->delete();
        return response()->json(null, 204);
    }
}
