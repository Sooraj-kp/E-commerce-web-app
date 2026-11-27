<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Services\RazorpayService;
use App\Models\Order;


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
            'address_id' => 'required|integer|exists:addresses,id',
            'payment_method'=>'required|in:cod,razorpay',
        ]);
        $paymentMethod = \App\Models\PaymentMethod::where(
            'code',
            $validated['payment_method'],

        )->where('is_active',true)->firstOrfail();

        $gatewayConfig = null;
        if($paymentMethod->code === 'razorpay'){
            $gatewayConfig = $paymentMethod->gateway()->where('is_active',true)->first()?->configs()->where('is_active',true)->where('mode','test')->first();
            if(!$gatewayConfig){
                return back()->with('error','RazorPay payment gateway configuration is unavailabel');
            }
        }
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
                $buyNow,
                $paymentMethod,
                $gatewayConfig
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
                Payment::create([
                    'order_id'=>$order->id,
                    'payment_method_id'=>$paymentMethod->id,
                    'payment_gateway_config_id' => $gatewayConfig?->id,
                    'amount'=>$total,
                    'currency'=>'INR',
                    'status'=>'pending',
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
        if ($paymentMethod->code === 'razorpay') {

            $payment = Payment::where('order_id', $order->id)
                ->firstOrFail();

            $amountInPaise = (int) round($order->total_amount * 100);

            try {
                $razorpayService = app(RazorpayService::class);

                $razorpayOrder = $razorpayService->createOrder(
                    $amountInPaise,
                    $order->order_number
                );

                $payment->update([
                    'gateway_order_id' => $razorpayOrder['id'],
                ]);

            } catch (\Exception $e) {
                return redirect()
                    ->route('customer.orders.show', $order->id)
                    ->with('error', 'Unable to initiate Razorpay payment. Please contact support.');
            }
        }

       
        if ($buyNow) {
            session()->forget('buy_now');
        }
        if($paymentMethod->code === 'razorpay'){
            return redirect()->route('customer.payment',$order->id);
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
    public function payment(Order $order){
        $order= auth()->user()->orders()->where('id',$order->id)->firstOrFail();

        $alreadyPaid =  Payment ::where('order_id',$order->id)->where('status','paid')->exists();
        if($alreadyPaid){
            return redirect()->route('customer.orders.show',$order->id)->with('error','This order already Paid');

        }
        $payment = Payment:: where('order_id',$order->id)->where('status','pending')->whereNotNull('gateway_order_id')->latest('id')->first();

        if(!$payment){
            return redirect()->route('customer.orders.show',$order->id)->with('error','No valid Payment attempt found');

        }
        return view('customer.checkout.payment',compact('order','payment'));
    }
    public function verifyPayment (Request $request,Order $order)
    {
        $validated=$request->validate([
            'razorpay_payment_id'=>'required|string',
            'razorpay_order_id'=>'required|string',
            'razorpay_signature'=>'required|string',
        ]);

        $order = auth()->user()->orders()->where('id',$order->id)->firstOrFail();
        $payment = Payment::where('order_id',$order->id)->where('gateway_order_id',$validated['razorpay_order_id'])->firstOrFail();
        if($payment->status === 'paid' ){
            return redirect()->route('customer.orders.show',$order->id)->with('success','Payment Already completed');
        }
        if($payment->status !==  'pending'){
            return redirect()->route('customer.orders.show',$order->id)->with('error','This Payment cannot be verified again');
        }
        $razorpayService = app(RazorpayService::class);
        $verified = $razorpayService->verifyPayment(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );
        if(!$verified){
            return redirect()->route('customer.orders.show',$order->id)->with('error','Payment verification failed');
        }
        try {
            $razorpayPayment=$razorpayService->fetchPayment(
                $validated['razorpay_payment_id']
            );
        } catch (\Throwable $e){
            report($e);
            return redirect()->route('customer.orders.show',$order->id)->with('error','Unable to retrive payment details . Please try again '); 

        }
        if($razorpayPayment['order_id'] !== $payment->gateway_order_id){
            return redirect()->route('customer.orders.show',$order->id)->with('error','Payment Id doesnt match');
        }
        $expectedAmount=(int) round((float) $payment->amount * 100);
        if((int) $razorpayPayment['amount'] !== $expectedAmount){
            return redirect()->route('customer.orders.show',$order->id)->with('error','Payment Amount is mismatch');

        }
        if($razorpayPayment['currency'] !== 'INR'){
            return redirect()->route('customer.orders.show',$order->id)->with('error','Invalid Payment Currency');
        }
        if($razorpayPayment ['status'] !== 'captured'){
            return redirect()->route('customer.orders.show',$order->id)->with('error',' Payment has not  captured');
        }

        $updated = DB::transaction(function() use($payment,$order,$validated) {
            $lockedPayment=Payment::where('id',$payment->id)->lockForUpdate()->firstOrFail();
            if($lockedPayment ->status !== 'pending'){
                return false;

            }
            $lockedPayment->update([
                'transaction_id'=>$validated['razorpay_payment_id'],
                'status'=>'paid',
                'paid_at'=>now(),
                'failure_reason'=>null


            ]);

            $order->update([
                'status'=> 'confirmed',

            ]);
            $order->statusHistories()->create([
                'status'=>'confirmed',
                'description'=>'Payment completed successfully through razorpay',
                'updated_by'=>auth()->id(),
            ]);
            return true;


        });
        if(!$updated){
            return redirect()->route('customer.orders.show',$order->id)->with('error','This payment has already been processed');
        }
        return redirect()->route('customer.orders.show',$order->id)->with('success','Payment Completed Successfully');
    }
    public function retryPayment(Order $order) {
        $user = auth()->user();
        $order = $user->orders()->where('id',$order->id)->firstOrFail();
        if (in_array($order->status, ['cancelled', 'delivered'], true)) {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'Payment retry is not available for this order.');
        }
        $alreadyPaid = Payment :: where ('order_id',$order->id)->where('status','paid')->exists();
        if($alreadyPaid){
            return redirect()->route('customer.orders.show',$order->id)->with('error','This Payment is already paid');

        }
        $previousPayment = Payment::where('order_id',$order->id)->with(['paymentMethod','gatewayConfig'])->latest('id')->first();
        if(!$previousPayment || $previousPayment ->paymentMethod?->code !== 'razorpay' ){
            return redirect()->route('customer.orders.show',$order->id)->with('error','Razorpay retry is not available for this order.');
        }
        $gatewayConfig = $previousPayment->gatewayConfig;
        if(!$gatewayConfig || !$gatewayConfig->is_active || $gatewayConfig->mode !== 'test') {
            return redirect()->route('customer.orders.show',$order->id)->with('error','Razorpay configuration');
        }

        try {
                $newPayment = DB::transaction(function () use(
                    $gatewayConfig,$previousPayment,$order

                )
                {
                    $lockOrder = Order :: where('id',$order->id)->lockForUpdate()->firstOrFail();
                    $alreadyPaid = Payment :: where('order_id',$lockOrder->id)->where('status','paid')->exists();
                    if($alreadyPaid){
                        throw new \RuntimeException('this order has already paid.');

                    }
                    $payment = Payment::create([
                        'order_id'=>$lockOrder->id,
                        'payment_method_id'=>$previousPayment->payment_method_id,
                        'payment_gateway_config_id' => $gatewayConfig->id,
                        'amount' => $lockOrder->total_amount,
                        'currency' => 'INR',
                        'status' => 'pending',

                ]);
                return $payment;
            });

            $amountInPaise = (int) round($newPayment->amount*100);
            $razorpayService = app(RazorpayService::class);
            $razorpayOrder = $razorpayService -> createOrder(
                $amountInPaise,
                $order->order_number . '-RETRY-' . $newPayment->id
            );
            $newPayment ->update([
                'gateway_order_id' => $razorpayOrder['id'],

            ]);
            return redirect()->route('customer.payment',$order->id);
        } catch (\Throwable $e){
            report($e);

            return redirect()->route('customer.orders.show',$order->id)->with('error','Unable to retry payment . Please try again');
        }

    }
}
