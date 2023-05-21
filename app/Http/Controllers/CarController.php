<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CarRepository;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    protected $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        $car_repository = new CarRepository($this->car);

        if($request->has('fields_version')) {
            $fields_version = 'version:id,'.$request->fields_versions;
            $car_repository->selectFieldsRelatedRegisters($fields_version);
        } else {
            $car_repository->selectFieldsRelatedRegisters('version');
        }

        if($request->has('filter')) {
            $car_repository->filter($request->filter);
        }

        if($request->has('fields')) {
            $car_repository->selectFields($request->fields);
        } 

        return response()->json($car_repository->getResponse(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        // Validate request
        $request->validate($this->car->rules());

        // Create a new car
        $car = $this->car->create([
            'version_id' => $request->version_id,
            'reg' => $request->reg,
            'available' => $request->available,
            'ml' => $request->ml,
        ]);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) : JsonResponse
    {
        // Get a single car
        $car = $this->car->with('version')->find($id);
        if (is_null($car)) {
            return response()->json(['error' => "Car with id $id does not exist"], 404);
        }
        return response()->json($car, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        // Update a Car
        $car = $this->car->find($id);
        if (is_null($car)) {
            return response()->json(['error' => "Unable to update - Car with id $id does not exist"], 404);
        }

        $dinamicRules = [];

        // dinamic validation
        foreach ($car->rules($id) as $input => $rule) {
            if (array_key_exists($input, $request->all())) {
                $dinamicRules[$input] = $rule;
            }
        }

        // validation
        $request->validate($dinamicRules);

        // Update the fields based on the request data
        if ($request->method() === 'PATCH') {
            // Update only the specified fields
            $car->fill($request->only(array_keys($dinamicRules)));
        } else {
            // Update all fields for PUT requests
            $car->fill($request->all());
        }

        $car->save();

        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) : JsonResponse
    {
        // Delete a car
        $car = $this->car->find($id);
        if (is_null($car)) {
            return response()->json(['error' => "Unable to delete - car with id $id does not exists"], 404);
        }

        // Delete the car
        $car->delete();
        return response()->json(null, 204);
    }
}
