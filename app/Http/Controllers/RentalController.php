<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRentalRequest;
use App\Repositories\RentalRepository;
use Illuminate\Http\JsonResponse;

class RentalController extends Controller
{
    private $rental;
    public function __construct (Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        $rental_repository = new RentalRepository($this->rental);

        if($request->has('filter')) {
            $rental_repository->filter($request->filter);
        }

        if($request->has('fields')) {
            $rental_repository->selectFields($request->fields);
        } 

        return response()->json($rental_repository->getResponse(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        //dd($request->all());
        // Validate request
        $request->validate($this->rental->rules());

        // Create a new customer
        $rental = $this->rental->create([
            'customer_id' => $request->customer_id,
            'car_id' => $request->car_id,
            'starting_date' => $request->starting_date,
            'planned_return_date' => $request->planned_return_date,
            'end_date' => $request->end_date,
            'value' => $request->value,
            'initial_ml' => $request->initial_ml,
            'final_ml' => $request->final_ml,
        ]);

        return response()->json($rental, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        // Get a single customer
        $rental = $this->rental->find($id);
        if (is_null($rental)) {
            return response()->json(['error' => "Rental with id $id does not exist"], 404);
        }
        return response()->json($rental, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        // Update a Customer
        $rental = $this->rental->find($id);
        if (is_null($rental)) {
            return response()->json(['error' => "Unable to update - Rental with id $id does not exist"], 404);
        }

        $dinamicRules = [];

        // dinamic validation
        foreach ($rental->rules($id) as $input => $rule) {
            if (array_key_exists($input, $request->all())) {
                $dinamicRules[$input] = $rule;
            }
        }

        // validation
        $request->validate($dinamicRules);

        // Update the fields based on the request data
        if ($request->method() === 'PATCH') {
            // Update only the specified fields
            $rental->fill($request->only(array_keys($dinamicRules)));
        } else {
            // Update all fields for PUT requests
            $rental->fill($request->all());
        }

        $rental->save();

        return response()->json($rental, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) : JsonResponse
    {
        // Delete a rental
        $rental = $this->rental->find($id);
        if (is_null($rental)) {
            return response()->json(['error' => "Unable to delete - customer with id $id does not exists"], 404);
        }

        // Delete the customer
        $rental->delete();
        return response()->json(null, 204);
    }
}
