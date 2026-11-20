<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(){
        $orders=Order::with('user','address')->latest()->paginate(10);
        return view('admin.orders.index',compact('orders'));
    }
    public function show($id){
        $orders=Order::with([
            'user',
            'address',
            'items.product',
            'assignedStaff',

        ])->findOrFail($id);

        $staffMembers=User::where('role','staff')->get();

        return view('admin.orders.show',compact('orders','staffMembers'));
    }
    public function updateStatus($id,Request $request){
        $orders=Order::findOrFail($id);
        $validated=$request->validate([
            'status'=>'required|in:confirmed,processing,shipped,out_for_delivery,delivered,cancelled',

        ]);
        if(!$orders->canTransitionTo($validated['status'])){
            return back()->withErrors(['status'=>'Invalid  order status  transition'])->withInput();
        }
        DB::transaction(function() use ($orders,$validated){
            $orders->update([
                'status'=>$validated['status']

            ]);
            $orders->statusHistories()->create([
            'status'=>$validated['status'],
            'description'=>'Order status updated to ' .str_replace('_',' ',$validated['status']),
            'updated_by'=>auth()->id(),

        ]);

        });
        
        return redirect()->route('admin.orders.show',$orders->id)->with('success','Order Status Updated Successfully');
    }
    public function assignStaff(Request $request,$id){
        $orders=Order::findOrfail($id);
        $validated=$request->validate([
            'assigned_staff_id'=>'required|exists:users,id',

        ]);
        $staff=User::where('id',$validated['assigned_staff_id'])->where('role','staff')->firstOrfail();
        $orders->update([
            'assigned_staff_id'=>$staff->id,
        ]);
        return redirect()->route('admin.orders.show',$orders->id)->with('success','Staff assigned successfully');

    }
}
