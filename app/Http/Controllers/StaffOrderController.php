<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StaffOrderController extends Controller
{
    public function index(){
        $staff=auth()->user();
        if($staff->role != 'staff'){
            abort(403);
        }
        $orders=Order::with('user','address')->where('assigned_staff_id',$staff->id)->latest()->paginate(10);
        return view('staff.orders.index',compact('orders'));    

    }
    public function show($id){
        $staff=auth()->user();
        if($staff->role != 'staff'){
            abort(403);
        }
        $orders=Order::with([
            'user',
            'address',
            'items.product'

        ])->where('assigned_staff_id',$staff->id)->findOrFail($id);
        return view('staff.orders.show',compact('orders'));  


    }
    public function updateStatus(Request $request,$id){
        $staff=auth()->user();
        if($staff->role != 'staff'){
            abort(403);
        }
        $orders=Order::where('assigned_staff_id',$staff->id)->findOrFail($id);
        $validated=$request->validate([
            'status'=>'required|in:processing,shipped,out_for_delivery,delivered',
        ]);
        if(!$orders->canTransitionTo($validated['status'])){
            return back()->withErrors([
                'status'=>'Invalid order status transition'

            ])->withInput();
        }
        DB::transaction(function () use($orders,$validated){
            $orders->update([
                'status'=>$validated['status'],
            ]);
            $orders->statusHistories()->create([
                'status'=>$validated['status'],
                'description'=>'Order status updated to' .str_replace('_',' ',$validated['status']),
                'updated_by'=>auth()->id(),

            ]);

        });

            
        return redirect()->route('staff.orders.show',$orders->id)->with('success','Order Status  updated successfully ');

    }
}
