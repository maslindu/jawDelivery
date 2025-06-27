<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverOrderController extends Controller
{
    public function readyOrders()
    {
        // Ambil pesanan dengan status "shipped" (siap diantar)
        $orders = Order::with(['user', 'address', 'menus'])
                      ->where('status', 'shipped')
                      ->whereNull('driver_id') // Belum diambil driver
                      ->orderBy('created_at', 'asc')
                      ->get();

        return view('drivers.ready-orders', compact('orders'));
    }

    public function processingOrders()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return view('drivers.processing-orders', ['orders' => collect()]);
        }

        // Orders yang sedang diproses oleh driver ini
        $orders = Order::with(['user', 'address', 'menus'])
                      ->where('driver_id', $driver->id)
                      ->whereIn('status', ['on_delivery'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('drivers.processing-orders', compact('orders'));
    }

    public function deliveryHistory()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return view('drivers.delivery-history', ['orders' => collect()]);
        }

        // History pengantaran yang sudah selesai
        $orders = Order::with(['user', 'address', 'menus'])
                      ->where('driver_id', $driver->id)
                      ->whereIn('status', ['delivered', 'completed'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('drivers.delivery-history', compact('orders'));
    }

    public function takeOrder(Request $request, $orderId)
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver profile tidak ditemukan'
            ], 404);
        }

        if ($driver->status !== 'active' || !$driver->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Driver tidak aktif atau tidak tersedia'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::where('id', $orderId)
                         ->where('status', 'shipped')
                         ->whereNull('driver_id')
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak tersedia atau sudah diambil driver lain'
                ], 404);
            }

            // Update order dengan driver dan ubah status
            $order->update([
                'driver_id' => $driver->id,
                'status' => 'on_delivery'
            ]);

            DB::commit();

            Log::info('Driver took order', [
                'driver_id' => $driver->id,
                'order_id' => $orderId,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diambil'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error taking order', [
                'driver_id' => $driver->id ?? null,
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil pesanan'
            ], 500);
        }
    }

    public function completeDelivery(Request $request, $orderId)
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver profile tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $order = Order::where('id', $orderId)
                         ->where('driver_id', $driver->id)
                         ->where('status', 'on_delivery')
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau tidak dalam status pengantaran'
                ], 404);
            }

            // Update status order menjadi delivered
            $order->update([
                'status' => 'delivered'
            ]);

            // Increment total deliveries driver
            $driver->incrementDeliveries();

            DB::commit();

            Log::info('Driver completed delivery', [
                'driver_id' => $driver->id,
                'order_id' => $orderId,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengantaran berhasil diselesaikan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error completing delivery', [
                'driver_id' => $driver->id ?? null,
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyelesaikan pengantaran'
            ], 500);
        }
    }
}
