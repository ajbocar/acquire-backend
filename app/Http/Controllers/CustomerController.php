<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Customer API",
 *     version="1.0.0",
 *     description="API for managing customers"
 * )
 *
 * @OA\Tag(
 *     name="Customers",
 *     description="Operations related to customers"
 * )
 */
class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers",
     *     summary="Get all customers",
     *     description="Retrieve a list of all customers.",
     *     operationId="getCustomers",
     *     tags={"Customers"},
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No customers found",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="No customers found"))
     *     )
     * )
     */
    public function index()
    {
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            return response()->json(['message' => 'No customers found'], Response::HTTP_NOT_FOUND);
        }
        return $customers;
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     description="Add a new customer to the database.",
     *     operationId="createCustomer",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error creating customer",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Error creating customer"))
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:customers',
            'age' => 'required|integer',
            'dob' => 'required|date',
        ]);

        try {
            $customer = Customer::create($request->all());
            return response()->json($customer, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating customer', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     summary="Get customer by ID",
     *     description="Retrieve details of a specific customer.",
     *     operationId="getCustomerById",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer details",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Customer not found"))
     *     )
     * )
     */
    public function show(Customer $customer)
    {
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     summary="Update customer",
     *     description="Update the details of an existing customer.",
     *     operationId="updateCustomer",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Customer not found"))
     *     )
     * )
     */
    public function update(Request $request, Customer $customer)
    {
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'last_name' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|string|email|max:50|unique:customers,email,' . $customer->id,
            'age' => 'sometimes|required|integer',
            'dob' => 'sometimes|required|date',
        ]);

        try {
            $customer->update($request->all());
            return $customer;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating customer', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     summary="Delete customer",
     *     description="Delete an existing customer.",
     *     operationId="deleteCustomer",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Customer not found"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting customer",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Error deleting customer"))
     *     )
     * )
     */
    public function destroy(Customer $customer)
    {
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $customer->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting customer', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
