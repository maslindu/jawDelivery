<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\UserAddress;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $userId = auth()->id();

        $userAddress = UserAddress::where('user_id', $userId)
                        ->where('is_primary', true)
                        ->first();

        $cartItems = Cart::with('menu')->where('user_id', $userId)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->menu->price * $item->quantity;
        }

        $shipping = 10000;
        $adminFee = 1000;
        $total = $subtotal + $shipping + $adminFee;

        return view('checkout', compact('cartItems', 'userAddress', 'subtotal', 'shipping', 'adminFee', 'total'));
    }



    public function create()
    {
        //
    }

    public function store(Request $request)
    {
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

}
