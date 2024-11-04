<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $customers = Customer::all();

        if (count($customers) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $customers,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        Customer::create($validatedData);

        // Handle Upload Image
        /* if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

            $file->storeAs('products/', $filename, 'public');
            $product->update([
                'image' => $filename
            ]);
        } */

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $customer,
            'errors' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|max:50|unique:customers,email,'.$id,
            'phone' => 'required|string|max:25|unique:customers,phone,'.$id,
            'address' => 'required|string|max:100',
            'type' => 'required|string',
            'bank_name' => 'max:25',
            'account_holder' => 'max:50',
            'account_number' => 'max:25',
            'photo' => 'image|file|max:1024'
        ]);

        // Action
        $post = Customer::findOrFail($id);

        $post->name = $request->name;
        $post->email = $request->email;
        $post->phone = $request->phone;
        $post->address = $request->address;
        $post->type = $request->type;
        $post->bank_name = $request->bank_name;
        $post->account_holder = $request->account_holder;
        $post->account_number = $request->account_number;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully!',
            'data' => $post,
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteCustomer = Customer::findOrFail($id);

        $deleteCustomer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!',
            'data' => '',
            'errors' => []
        ]);
    }
}
