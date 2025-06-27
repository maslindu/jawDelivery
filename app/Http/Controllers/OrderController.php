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
        $user = Auth::user();
        
        $histories = Order::with(['menus', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'invoice' => $order->invoice,
                    'date' => $order->created_at->format('Y-m-d'),
                    'items' => $order->menus->sum('pivot.quantity'),
                    'buyer' => $order->user->name ?? $order->user->username,
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
                'status' => Order::STATUS_PENDING, // Gunakan konstanta
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
        try {
            $user = Auth::user();

            $order = Order::with(['menus', 'address'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return view('order', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error showing order', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('history')->with('error', 'Pesanan tidak ditemukan');
        }
    }

    // Method untuk mendapatkan status terbaru order
    public function getStatus($id)
    {
        try {
            $user = Auth::user();
            
            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => $order->status,
                'status_label' => $this->getStatusLabel($order->status),
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting order status', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil status pesanan'
            ], 500);
        }
    }

    // Helper method untuk mendapatkan label status yang konsisten
    private function getStatusLabel($status)
    {
        return Order::getStatusOptions()[$status] ?? ucfirst($status);
    }
}
