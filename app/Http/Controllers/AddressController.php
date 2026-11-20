<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create(){
        $user=auth()->user();
        if($user->role != 'customer'){
            abort(403);
        }
        return view('customer.address.create');
    }
    public function store(Request $request){
        $user=auth()->user();
        if($user->role != 'customer'){
            abort(403);
        }
        $validated=$request->validate([
            'address_line'=>'required|string|max:191',
            'city'=>'required|string|max:191',
            'state'=>'required|string|max:191',
            'postal_code'=>'required|string|max:191',
            'country'=>'required|string|max:191',
        ]);
        $user->addresses()->create($validated);
        return redirect()->route('customer.account')->with('Delivary Address Added Successfully');
    }
    public function destroy($id){
        $user=auth()->user();
        if($user->role != 'customer'){
            abort(403);
        }
        $address=$user->addresses()->findOrFail($id);
        $address->delete();

        return redirect()->route('customer.account')->with('success','Address  deleted Successfully');  
    }
    public function edit($id){
        $user=auth()->user();
        if($user->role !== 'customer'){
            abort(403);
        }
        $address=$user->addresses()->findOrFail($id);
        return view('customer.address.edit',compact('address'));
    }  
    public function update($id , Request $request){
        $user=auth()->user();
        if($user->role !== 'customer'){
            abort(403);
        }
        $address=$user->addresses()->findOrFail($id);
        $validated=$request->validate([
            'address_line'=>'required|string|max:191',
            'city'=>'required|string|max:191',
            'state'=>'required|string|max:191',
            'postal_code'=>'required|string|max:191',
            'country'=>'required|string|max:191',

        ]);
        $address->update($validated);
        return redirect()->route('customer.account')->with('success','Address Update Successfully');

    }
    
}
