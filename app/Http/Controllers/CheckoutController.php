<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CheckoutController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();

        if ($user->role != 'customer') {
            abort(403);
        }

        $buyNow = session('buy_now');

        $cart = null;
        $product = null;
        $quantity = 0;
        $total = 0;

        if ($buyNow) {

            
            $product = Product::with('images')
                ->findOrFail($buyNow['product_id']);

            $quantity = $buyNow['quantity'];
            $total = $product->price * $quantity;

        } else {

           
            $cart = $user->carts()
                ->with('items.product.images')
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your Cart is Empty. Please add products.');
            }
        }

        $addresses = $user->addresses()->get();

        return view('customer.checkout.index', compact(
            'cart',
            'product',
            'quantity',
            'total',
            'buyNow',
            'addresses'
        ));
    }

    
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role != 'customer') {
            abort(403);
        }

        $validated = $request->validate([
            'address_id' => 'required|integer|exists:addresses,id'
        ]);

        $address = $user->addresses()->find($validated['address_id']);

        if (!$address) {
            abort(403);
        }

        $buyNow = session('buy_now');

        $cart = null;
        $orderItems = collect();

        
        if (!$buyNow) {

            $cart = $user->carts()
                ->with('items.product')
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Cart is Empty');
            }
        }

        
        if ($buyNow) {

            $orderItems->push([
                'product_id' => $buyNow['product_id'],
                'quantity' => $buyNow['quantity']
            ]);

        } else {

            foreach ($cart->items as $item) {

                $orderItems->push([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);
            }
        }

        try {

            $order = DB::transaction(function () use (
                $user,
                $address,
                $cart,
                $orderItems,
                $buyNow
            ) {

                $orderNumber = 'ORD-' . now()->format('Ymd') . '-'
                    . strtoupper(uniqid());

                $total = 0;
                $products = collect();

               
                foreach ($orderItems as $item) {

                    if ($item['quantity'] < 1) {
                        throw new \Exception('Invalid product quantity');
                    }

                    $product = Product::where('id', $item['product_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$product) {
                        throw new \Exception('Product not found');
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception(
                            'Insufficient stock for ' . $product->name
                        );
                    }

                    $products->put($item['product_id'], $product);

                    $total += $product->price * $item['quantity'];
                }

                
                $order = $user->orders()->create([
                    'address_id' => $address->id,
                    'order_number' => $orderNumber,
                    'total_amount' => $total,
                    'status' => 'pending'
                ]);

                $order->statusHistories()->create([
                    'status'=>'pending',
                    'description'=>'Order Placed Successfully',
                    'updated_by'=>$user->id

                ]);

              
                foreach ($orderItems as $item) {

                    $product = $products->get($item['product_id']);

                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                
                if (!$buyNow) {
                    $cart->items()->delete();
                }

                return $order;
            });

        } catch (\Exception $e) {

            return redirect()->route('checkout.index')
                ->with('error', $e->getMessage());
        }

       
        if ($buyNow) {
            session()->forget('buy_now');
        }

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Your order has been placed successfully');
    }

    
    public function buyNow(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$product->stock
        ]);

        $quantity = $validated['quantity'];

        if ($product->stock < 1) {
            return back()->with('error', 'Product is Out of Stock');
        }

        if ($quantity > $product->stock) {
            return back()->with(
                'error',
                'Requested quantity exceeds available stock'
            );
        }

        session([
            'buy_now' => [
                'product_id' => $product->id,
                'quantity' => $quantity
            ]
        ]);

        return redirect()->route('checkout.index');
    }
}
