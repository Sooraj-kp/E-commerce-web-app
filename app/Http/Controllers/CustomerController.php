<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
public function index()
{
    $customers = Customer::join(
        'users',
        'customers.user_id',
        '=',
        'users.id'
    )
    ->select(
        'customers.id',
        'customers.phone',
        'users.name',
        'users.email'
    )
    ->get();

    return view('customers.index', compact('customers'));
}
}
