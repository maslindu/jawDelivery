<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();
        $menuId = $request->menu_id;
        $quantity = $request->quantity;

        $cartItem = Cart::where('user_id', $userId)
                        ->where('menu_id', $menuId)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity = $cartItem->quantity + $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'menu_id' => $menuId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json(['message' => 'Item added to cart']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cart->quantity = $request->quantity;
        $cart->save();

        return $this->cartTotalsJson($id);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cart->delete();

        return $this->cartTotalsJson($id);
    }

private function cartTotalsJson($id)
{
    $cartItems = Cart::with('menu')->where('user_id', auth()->id())->get();

    // Try to get the updated item, but allow null if not found
    $updatedItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();

    $subtotal = $cartItems->sum(fn($item) => $item->menu->price * $item->quantity);
    $shipping = 10000;
    $adminFee = 1000;
    $total = $subtotal + $shipping + $adminFee;

    return response()->json([
        'quantity' => $updatedItem ? $updatedItem->quantity : null,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'adminFee' => $adminFee,
        'total' => $total
    ], 200);
}

}
