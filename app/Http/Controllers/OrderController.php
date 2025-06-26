<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $histories = Order::with(['menus', 'user'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'invoice' => $order->invoice,
                    'date' => $order->created_at->format('Y-m-d'), // for display
                    'items' => $order->menus->sum('pivot.quantity'),
                    'buyer' => $order->user->name,
                    'payment' => $order->payment_method,
                    'status' => $order->status,
                    'total' => $order->subtotal + $order->shipping_fee + $order->admin_fee,
                    'order_number' => $order->invoice,
                ];
            });
        return view('history', compact('histories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        $address = UserAddress::where('user_id', $user->id)->where('is_primary', true)->first();

        if (!$address) {
            Log::warning('User has no primary address.', ['user_id' => $user->id]);
            return response()->json(['message' => 'Alamat belum diatur.'], 400);
        }

        $cartItems = Cart::with('menu')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty.', ['user_id' => $user->id]);
            return response()->json(['message' => 'Keranjang kosong.'], 400);
        }

        $shipping = 10000;
        $adminFee = 1000;
        $subtotal = $cartItems->sum(function ($item) {
            return $item->menu->price * $item->quantity;
        });


        $invoice_number = 'JD-' . now()->format('YmdHis') . '-' . $user->id . '-' . Str::upper(Str::random(4));

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'subtotal' => $subtotal,
                'payment_status' => 'unpaid',
                'shipping_fee' => $shipping,
                'admin_fee' => $adminFee,
                'status' => 'pending',
                'invoice' => $invoice_number
            ]);

            foreach ($cartItems as $item) {
                $order->menus()->attach($item->menu_id, [
                    'quantity' => $item->quantity,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();


            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Order creation failed.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Terjadi kesalahan saat membuat pesanan.'], 500);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        $order = Order::with(['menus', 'address'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('order', compact('order'));
    }
}
