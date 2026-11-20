<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function Index(){
        $storename="LORENZZ ECOM STORE";
        $message="WELCOME to The Ecom Store";
        $isopen=false;
        $products=[
                    ['name'=>'Laptop','price'=>'60000'],
                    ['name'=>'Mobile','price'=>'25000'],
                    ['name'=>'Earphone','price'=>'1000']
            ];

        return view('about',
        ['storename'=>$storename,
        'message'=>$message,
        'isopen'=>$isopen,
        'products'=>$products
        ]);
    }
}
