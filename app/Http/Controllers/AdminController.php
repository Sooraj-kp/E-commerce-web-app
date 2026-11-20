<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        $totalProducts= Product::count();
        $totalOrders=Order::count();
        $totalCustomer=Customer::count();
        $totalStaff = \App\Models\User::where('role', 'staff')->count();
        return view('admin.dashboard',compact('totalProducts','totalOrders','totalCustomer','totalStaff'));
    }
}
