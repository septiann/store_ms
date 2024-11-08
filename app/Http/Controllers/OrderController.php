<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use DB;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        if (count($orders) < 1) {
            throw new NotFoundHttpException('Orders not found');
        }

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $orders,
            'errors' => ''
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // Validation
        $validateOrder = $request->validated();

        // Action
        DB::beginTransaction();

        $dateNow = date('dmY');
        $user_id = auth()->user()->id;

        $carts = Cart::with('products')->where('user_id', $user_id)->get();

        $sub_total = 0;
        $tax_total = 0;
        foreach ($carts as $cart) {
            $sub_total += $cart->sub_total;
            $tax_total += (11 / 100) * $sub_total;
        }

        $total_amount = $sub_total + $tax_total;

        if ($validateOrder['pay'] < $total_amount) {
            throw new Exception('Total payment does not match with total amount');
        }


        $validateOrder = [
            'customer_id' => $validateOrder['customer_id'],
            'payment_type' => $validateOrder['payment_type'],
            'pay' => $validateOrder['pay'],
            'order_date' => date('Y-m-d H:i:s'),
            'status' => OrderStatus::Pending->value,
            'total_products' => count($carts),
            'sub_total' => $sub_total,
            'vat' => $tax_total,
            'total' => $total_amount,
            'invoice_no' => IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 20,
                'prefix' => 'INV/'.$dateNow.'/',
                'reset_on_prefix_change' => true
            ]),
            'due' => ($total_amount - $validateOrder['pay']),
        ];

        $storeOrder = Order::create($validateOrder);

        if (! $storeOrder) {
            DB::rollback();

            throw new Exception('Failed to store order data.');
        }

        // Create Order Detail
        foreach ($carts as $cart) {
            $dataOrderDetail;
            $dataOrderDetail['order_id'] = $storeOrder->id;
            $dataOrderDetail['product_id'] = $cart->product_id;
            $dataOrderDetail['quantity'] = $cart->quantity;
            $dataOrderDetail['unit_cost'] = $cart->products->selling_price;
            $dataOrderDetail['total'] = $cart->products->selling_price * $cart->quantity;

            $storeOrderDetail = OrderDetail::create($dataOrderDetail);

            if (! $storeOrderDetail) {
                DB::rollback();

                throw new Exception('Failed to store order detail data.');
            }
        }

        // Delete Cart
        $cartsDelete = Cart::where('user_id', $user_id)->delete();

        if (! $cartsDelete) {
            DB::rollback();

            throw new Exception('Failed to delete carts data.');
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Successfully create an order. Please make a payment.',
            'data' => '',
            'errors' => ''
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $order = Order::with(['customer', 'details'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $order,
            'errors' => ''
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function processOrder(string $id)
    {
        $details = OrderDetail::where('order_id', $id)->get();

        // Reduce Product Stock
        DB::beginTransaction();
        foreach($details as $detail) {
            $product = Product::where('id', $detail->product_id)->update([
                'quantity' => DB::raw('quantity-'.$detail->quantity)
            ]);

            if (! $product) {
                DB::rollback();

                throw new Exception('Failed to reduce the product quantity');
            }
        }

        $order = Order::findOrFail($id);

        $updateOrder = $order->update([
            'status' => OrderStatus::Complete
        ]);

        if (! $updateOrder) {
            DB::rollback();

            throw new Exception('Failed to update order status');
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Order is complete. Thank you.',
            'data' => '',
            'errors' => ''
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancelOrder(string $id)
    {
        $order = Order::findOrFail($id);

        $updateOrder = $order->update([
            'status' => OrderStatus::Cancel
        ]);

        if (! $updateOrder) {
            throw new Exception('Failed to update order status');
        }

        return response()->json([
            'success' => true,
            'message' => 'Success cancelled order status.',
            'data' => '',
            'errors' => ''
        ], 200);
    }
}
