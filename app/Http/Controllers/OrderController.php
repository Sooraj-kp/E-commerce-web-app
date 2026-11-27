<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Models\Order;

class OrderController extends Controller
{
    
    public function index()
    {
        $orders = Order::all();

        return view('orders.index', compact('orders'));
    }

    
    public function show(Order $order)
    {
        $user = auth()->user();

        if (!$user || $user->role != 'customer') {
            abort(403);
        }

        if ($order->user_id != $user->id) {
            abort(403);
        }

        $order->load(['items.product.images' => function($query){
            $query->orderByDesc('is_primary')->orderBy('image_order');
        }, 'address','statusHistories.updatedBy',
                        
        ]);

        return view('customer.orders.show', compact('order'));
    }

    
        public function myOrders(Request $request)
        {
            $user = auth()->user();

            if (!$user || $user->role != 'customer') {
                abort(403);
            }

            $activeCount=$user->orders()->where('status','!=','cancelled')->count();
            $cancelCount=$user->orders()->where('status','cancelled')->count();
            $status = $request->query('status','active');


        $ordersQuery = $user->orders()
            ->with([
                'items.product.images' => function ($query) {
                    $query->orderByDesc('is_primary')
                        ->orderBy('image_order');
                },
                'latestPayment',
                'latestPayment.paymentMethod',
            ]);
            if($status === 'cancelled'){
                $ordersQuery->where('status','cancelled');
            } else {
                $status = 'active';
                $ordersQuery->where('status','!=','cancelled');
            }
            $orders=$ordersQuery->latest()->paginate(10)->withQueryString();   

            if($request->ajax()){
                return view('customer.orders.partials.list',compact('orders'));
            }

            return view('customer.orders.index', compact('orders','activeCount','cancelCount','status'));
        }
        public function cancel(Request $request, Order $order)
        {
        $user = auth()->user();

        if (!$user || $user->role !== 'customer') {
            abort(403);
        }

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($order, $validated, $user) {

            
            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$order->canBeCancelled()) {
                throw ValidationException::withMessages([
                    'order' => 'This order cannot be cancelled at its current status.'
                ]);
            }

            
            foreach ($order->items as $item) {

                $product = \App\Models\Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            
            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'cancellation_reason' => $validated['cancellation_reason'] ?? null,
                'cancelled_at' => now()
            ]);

            // Record status history
            $order->statusHistories()->create([
                'status' => Order::STATUS_CANCELLED,
                'description' => 'Order cancelled by customer',
                'updated_by' => $user->id
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Your order has been cancelled successfully.');
    }
}
