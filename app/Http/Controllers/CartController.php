<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);
        $user_id = auth()->user()->id;
        $carts = Cart::where('user_id', $user_id)->get();

        $validateCart = $request->validated();

        // Action
        $cartFindProduct = Cart::where([
            'user_id' => $user_id,
            'product_id' => $request->product_id
        ])->first();

        if ($cartFindProduct == null) {
            $validateCart['user_id'] = $user_id;
            $validateCart['sub_total'] = $product->selling_price * $request->quantity;

            Cart::create($validateCart);
        } else {
            $cartFindProduct->quantity += $request->quantity;
            $cartFindProduct->sub_total += $product->selling_price * $request->quantity;

            $cartFindProduct->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Success add product to cart',
            'data' => '',
            'errors' => ''
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $carts_arr = [];
        $user_id = auth()->user()->id;
        $carts = Cart::with('products')->where('user_id', $user_id)->get();
        $statusCode = 200;
        $message = "";

        $total = 0;
        $carts_arr['carts'] = $carts;
        foreach ($carts as $cart) {
            $total += $cart->sub_total;
        }
        $carts_arr['total'] = $total;
        $carts_arr['discount'] = 0;
        $carts_arr['tax'] = 0;

        if (count($carts) < 1) {
            $statusCode = 404;
            $message = "Cart is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $carts_arr,
            'errors' => ''
        ], $statusCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateQuantity(Request $request, string $id)
    {
        $user_id = auth()->user()->id;
        $cart = Cart::with('products')->where([
            'user_id' => $user_id,
            'id' => $id
        ])->first();

        if ($cart == null) {
            throw new NotFoundHttpException('Data is not available');
        }

        $type = $request->query('type');

        if ($type == 'in') {
            $cart->quantity += 1;
        } else if ($type == 'de') {
            $cart->quantity -= 1;
        } else {
            throw new NotFoundHttpException('Type is not available');
        }

        $cart->sub_total = $cart->quantity * $cart->products->selling_price;
        $cart->save();

        if ($cart->quantity == 0) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Success update quantity',
            'data' => '',
            'errors' => ''
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::with('products')->findOrFail($id);
        $productName = $cartItem->products->name;

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Success delete product '. $productName .' from cart',
            'data' => '',
            'errors' => ''
        ], 200);
    }
}
