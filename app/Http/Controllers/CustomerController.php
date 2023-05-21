<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        $customer_repository = new CustomerRepository($this->customer);

        if($request->has('filter')) {
            $customer_repository->filter($request->filter);
        }

        if($request->has('fields')) {
            $customer_repository->selectFields($request->fields);
        } 

        return response()->json($customer_repository->getResponse(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate($this->customer->rules());

        // Create a new customer
        $customer = $this->customer->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' =>$request->address,
            'postcode' => $request->postcode,
            'city' => $request->city,
            'county' => $request->county,
        ]);

        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Intenger  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        // Get a single customer
        $customer = $this->customer->find($id);
        if (is_null($customer)) {
            return response()->json(['error' => "Customer with id $id does not exist"], 404);
        }
        return response()->json($customer, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  Intenger  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // Update a Customer
        $customer = $this->customer->find($id);
        if (is_null($customer)) {
            return response()->json(['error' => "Unable to update - Customer with id $id does not exist"], 404);
        }

        $dinamicRules = [];

        // dinamic validation
        foreach ($customer->rules($id) as $input => $rule) {
            if (array_key_exists($input, $request->all())) {
                $dinamicRules[$input] = $rule;
            }
        }

        // validation
        $request->validate($dinamicRules);

        // Update the fields based on the request data
        if ($request->method() === 'PATCH') {
            // Update only the specified fields
            $customer->fill($request->only(array_keys($dinamicRules)));
        } else {
            // Update all fields for PUT requests
            $customer->fill($request->all());
        }

        $customer->save();

        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Intenger  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        // Delete a customer
        $customer = $this->customer->find($id);
        if (is_null($customer)) {
            return response()->json(['error' => "Unable to delete - customer with id $id does not exists"], 404);
        }

        // Delete the customer
        $customer->delete();
        return response()->json(null, 204);
    }
}
