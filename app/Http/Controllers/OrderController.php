<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __invoke()
    {
        //

        $orders = 'challenge';

        return view('front.user.orders.index', compact('orders'));
    }
}
