<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\StoreSupplierDetailRequest;
use App\Models\Supplier\MSupplier;
use App\Models\Supplier\TSupplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $suppliers = MSupplier::all();

        if (count($suppliers) < 1) {
            $message = "Data is empty";
        }

        for ($i = 0; $i < count($suppliers); $i++) {
            $supplierDetails = TSupplier::where('supplier_id',$suppliers[$i]->id)->get();

            $suppliers[$i]->detail = $supplierDetails;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $suppliers,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        MSupplier::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    public function storeDetail(StoreSupplierDetailRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        TSupplier::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Supplier Detail added successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = MSupplier::findOrFail($id);

        $supplierDetails = TSupplier::where('supplier_id',$supplier->id)->get();

        $supplier->detail = $supplierDetails;

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $supplier,
            'errors' => []
        ]);
    }

    public function showDetail(string $id)
    {
        $supplierDetail = TSupplier::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $supplierDetail,
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
            'contact_name' => 'required|string|max:15',
            'phone_number' => 'required|numeric|min_digits:9|max_digits:14|unique:m_suppliers,phone_number,'.$id,
            'address' => 'required'
        ]);

        $post = MSupplier::findOrFail($id);

        $post->name = $request->name;
        $post->contact_name = $request->contact_name;
        $post->phone_number = $request->phone_number;
        $post->address = $request->address;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully!',
            'data' => $post,
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = MSupplier::findOrFail($id);

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully!',
            'data' => '',
            'errors' => []
        ]);
    }
}
