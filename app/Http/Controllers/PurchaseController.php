<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseStatus;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $statusCode = 200;
        $purchases = Purchase::latest()->get();

        if (count($purchases) < 1) {
            $message = "Data is empty";
            $statusCode = 400;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $purchases,
            'errors' => []
        ], $statusCode);
    }

    public function getPurchaseByStatus(String $status) {
        // Validation
        if (! PurchaseStatus::tryFrom($status)) {
            throw new NotFoundHttpException("Purchase status not found");
        }

        // Action
        $purchases = Purchase::with(['supplier'])->where('status', $status)->get();
        $statusCode = 200;

        if (count($purchases) < 1) {
            $statusCode = 404;
        }

        return response()->json([
            'success' => true,
            'message' => "",
            'data' => $purchases,
            'errors' => []
        ], $statusCode);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        // Validation
        $supplier = Supplier::findOrFail($request->supplier_id);

        $validatePurchase = $request->validated();

        // Action
        $purchase = Purchase::create($validatePurchase);

        if ($request->products != null) {
            $purchase_details = [];

            foreach ($request->products as $product) {
                $purchase_details['purchase_id'] = $purchase['id'];
                $purchase_details['product_id'] = $product['product_id'];
                $purchase_details['quantity'] = $product['quantity'];
                $purchase_details['unit_cost'] = $product['unit_cost'];
                $purchase_details['total'] = $product['total'];
                $purchase_details['created_at'] = date('Y-m-d H:i:s');
                $purchase_details['updated_at'] = date('Y-m-d H:i:s');

                $purchase->details()->insert($purchase_details);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Purchase has been created",
            'data' => '',
            'errors' => ''
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->loadMissing(['supplier', 'details', 'createdBy', 'updatedBy'])->get();
        $products = PurchaseDetail::where('purchase_id', $purchase->id)->get();

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $purchase,
            'errors' => []
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $products = PurchaseDetail::where('purchase_id', $purchase->id)->get();

        foreach ($products as $product) {
            Product::where('id', $product->product_id)->update([
                'quantity' => DB::raw('quantity+'.$product->quantity)
            ]);
        }

        $post = Purchase::findOrFail($purchase->id)->update([
            'status' => PurchaseStatus::Complete,
            'updated_by' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase updated successfully',
            'data' => $post,
            'errors' => []
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully',
            'data' => '',
            'errors' => []
        ], 200);
    }
}
