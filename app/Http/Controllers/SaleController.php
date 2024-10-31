<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product\MProduct;
use App\Models\Product\TProduct;
use App\Models\Sale\MSale;
use App\Models\Sale\TSale;
use Illuminate\Http\Request;
use Exception;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function submitSales(StoreSaleRequest $request)
    {
        // Validation
        $validate_request = $request->validated();

        Employee::findOrFail($validate_request['employee_id']);
        Customer::findOrFail($validate_request['customer_id']);

        // Action
        try {
            DB::beginTransaction();

            $total_amount = 0;

            foreach($request->products as $product) {
                $stock = MProduct::select('initial_stock')->where('id', $product->product_id)->get();

                if ($stock < $product->quantity) {
                    DB::rollback();

                    return response()->json([
                        'success' => false,
                        'message' => 'Product stock not available!',
                        'data' => '',
                        'errors' => []
                    ], 403);
                }

                $total_amount += $product->quantity * $product->price;
            }

            // Insert to m_sale
            MSale::create([
                'employee_id' => $request->employee_id,
                'customer_id' => $request->customer_id,
                'total_amount' => $total_amount,
                'payment_method' => $request->payment_method
            ]);

            // Insert to t_sale
            foreach($request->products as $product) {
                TSale::create([
                    'sale_id' => $insert_sale->id,
                    'product_id' => $product->product_id,
                    'quantity' => $product->quantity,
                    'price' => $product->quantity * $product->price
                ]);

                // Update stok produk di m_produk
                $productCurr = MProduct::findOrFail($product->id);

                $productCurr->decrement('initial_stock', $product->quantity);

                // Insert to t_product
                TProduct::create([
                    'product_id' => $product->id,
                    'transaction_type' => 'OUT',
                    'quantity' => $product->quantity,
                    'amount' => $product->price,
                    'description' => 'Penjualan barang ke customer'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sales created successfully!',
                'data' => '',
                'errors' => []
            ], 200);
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}
