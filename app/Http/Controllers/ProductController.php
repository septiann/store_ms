<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $products = Product::all();

        if (count($products) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $products,
            'errors' => []
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        Product::create($validatedData);

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
            'message' => 'Product created successfully!',
            'data' => '',
            'errors' => []
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $product,
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
            'category_id'   => 'required|numeric',
            'unit_id'       => 'required|numeric',
            'name'          => 'required|string',
            'quantity'      => 'required|numeric',
            'buying_price'  => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock'         => 'required|numeric',
            'tax'           => 'nullable|numeric',
            'tax_type'      => 'nullable|numeric',
            'notes'         => 'nullable|max:100'
        ]);

        // Action
        $post = Product::findOrFail($id);

        $post->category_id  = $request->category_id;
        $post->unit_id      = $request->unit_id;
        $post->name         = $request->name;
        $post->quantity     = $request->quantity;
        $post->buying_price = $request->buying_price;
        $post->selling_price= $request->selling_price;
        $post->stock        = $request->stock;
        $post->tax          = $request->tax;
        $post->tax_type     = $request->tax_type;
        $post->notes        = $request->notes;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data' => $post,
            'errors' => []
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!',
            'data' => '',
            'errors' => []
        ], 200);
    }
}
