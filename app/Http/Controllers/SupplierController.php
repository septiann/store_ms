<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $suppliers = Supplier::all();

        if (count($suppliers) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $suppliers,
            'errors' => []
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        // Validation
        $validateSuppliers = $request->validated();

        // Action
        Supplier::create($validateSuppliers);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully!',
            'data' => '',
            'errors' => []
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $supplier,
            'errors' => []
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'nullable|email|max:50|unique:suppliers,email,'.$id,
            'phone' => 'required|string|max:25|unique:suppliers,phone,'.$id,
            'address' => 'required|string|max:100',
            'shop_name' => 'nullable|string|max:50',
            'type' => 'required|string|max:25',
            'photo' => 'nullable|image|file|max:1024',
            'bank_name' => 'nullable|max:25',
            'account_holder' => 'nullable|max:50',
            'account_number' => 'nullable|max:25',
        ]);

        // Action
        $post = Supplier::findOrFail($id);

        $post->name = $request->name;
        $post->email = $request->email;
        $post->phone = $request->phone;
        $post->address = $request->address;
        $post->shop_name = $request->shop_name;
        $post->type = $request->type;
        $post->photo = $request->photo;
        $post->bank_name = $request->bank_name;
        $post->account_holder = $request->account_holder;
        $post->account_number = $request->account_number;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully!',
            'data' => $post,
            'errors' => []
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteSupplier = Supplier::findOrFail($id);

        $deleteSupplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully!',
            'data' => '',
            'errors' => []
        ], 200);
    }
}
