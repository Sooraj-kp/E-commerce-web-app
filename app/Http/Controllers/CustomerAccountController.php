<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerAccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role != 'customer') {
            abort(403);
        }

        $addresses = $user->addresses()->get();

        return view('customer.account.index', compact('user', 'addresses'));
    }
}