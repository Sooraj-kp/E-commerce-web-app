<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\CartItem;

class CartController extends Controller
{
    public function add(Request $request,Product $product ) 
    {
        $user=auth()->user();
        if(!$user || $user->role !=='customer'){
            abort(403);
        }
        $validated=$request->validate([
            'quantity'=>'required|integer|min:1|max:' .$product->stock

        ]);
        $quantity=$validated['quantity'];
        $cart=$user->carts()->firstOrCreate([]);
        $cartItem=$cart->items()->where('product_id',$product->id)->first();

        if($product->stock<1){
            if($request->expectsJson()){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Product is Out Of Stock'

                ],422);
            }
            return back()->with('error','Product is out of stock');
        }
        if($quantity > $product->stock){
            if($request->expectsJson()){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Requested Quantity exeeds avilable stock'

                ],422);
            }
            return back()->with('error','Requested Quantity exeeds avilable stock');
        }

        if($cartItem){

            $newQuantity=$cartItem->quantity + $quantity;

            if($newQuantity > $product->stock){

                if($request->expectsJson()){
                    return response()->json([
                        'status'=>'error',
                        'message'=>'Maximum Available Stock Reached'
                    ],422);

                }
                    
                return back()->with('error','Maximum Available stock reached');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        }
        else{
            $cart->items()->create([
                'product_id'=>$product->id,
                'quantity'=>$quantity,

            ]); 
            
        }
        if($request->expectsJson()){

            return response()->json([
                'status'=>'success',
                'message'=>'Product added successfully'

            ]);

        }
        return redirect('/shop/'.$product->id)->with('success','Product Added Successfully');
    }
    public function index(){
        $user=auth()->user();
        if(!$user || $user->role !== 'customer'){
            abort(403);
        }
        $cart=$user->carts()->with('items.product.images')->first();
        return view('customer.cart.index',compact('cart'));
    }
    public function update(Request $request,CartItem $cartItem){

        $user=auth()->user();
        if(!$user || $user->role !== 'customer'){
            abort(403);

        }
        if($cartItem->cart->user_id != $user->id){
            abort(403);
        }
        $validated=$request->validate([
            'quantity'=>'required|integer|min:1'
        ]);

        $quantity=$validated['quantity'];
        $product=$cartItem->product;
        if($quantity>$product->stock){
            return back()->with('error','Only' .$product->stock. 'items are available');
        }
        $cartItem->quantity=$quantity;
        $cartItem->save();

        if($request->expectsJson()){
            return response()->json([
                'status'=>'success',
                'message'=>'Cart Updated Successfully',
                'quantity'=>$cartItem->quantity,
                'subtotal'=>$product->price*$cartItem->quantity

            ]);

        }
        return back()->with('success','Cart Item updated Successfully');
    }
    public function remove(CartItem $cartItem){
        $user=auth()->user();
        if(!$user || $user->role !== 'customer'){
            abort(403);
        }
        if($cartItem->cart->user_id != $user->id){
            abort(403);
        }
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success','Product removed Successfully');

    }
}