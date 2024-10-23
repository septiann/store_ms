<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductDetailRequest;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product\MProduct;
use App\Models\Product\TProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $products = MProduct::all();

        if (count($products) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $products,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        MProduct::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    public function restockProduct(StoreProductDetailRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        // Update initial_stock in m_product
        $update_stock = $this->updateStockProduct($validatedData);

        // Insert to t_products
        if ($update_stock) {
            TProduct::create($validatedData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product stock updated successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = MProduct::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $product,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'initial_stock' => 'required|numeric',
            'category_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'barcode' => 'required|string'
        ], Response::HTTP_OK);

        // Action
        $updateProduct = MProduct::findOrFail($id);

        $updateProduct->name = $request->name;
        $updateProduct->description = $request->description;
        $updateProduct->price = $request->price;
        $updateProduct->initial_stock = $request->initial_stock;
        $updateProduct->category_id = $request->category_id;
        $updateProduct->supplier_id = $request->supplier_id;
        $updateProduct->barcode = $request->barcode;

        $updateProduct->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data' => $updateProduct,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteProduct = MProduct::findOrFail($id);

        $deleteProduct->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_OK);
    }

    private function updateStockProduct($data)
    {
        // Check data in m_products by product_id
        $product = MProduct::findOrFail($data['product_id']);

        if ($data['transaction_type'] === "IN") { // Ini terjadi karena ada restock atau supply barang baru dari supplier.
            $product->initial_stock = $product->initial_stock + $data['quantity'];

            $product->save();

            return true;
        } else if ($data['transaction_type'] === "OUT") { // Ini jarang terjadi, bisa disebabkan barang rusak atau lain-lain.
            if ($product->initial_stock < $data['quantity']) {
                $product->initial_stock = 0;
            } else {
                $product->initial_stock = $product->initial_stock - $data['quantity'];
            }

            $product->save();

            return true;
        }

        return false;
    }
}
