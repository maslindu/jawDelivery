<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DriverOrderController extends Controller
{
    public function readyOrders()
    {
        try {
            // Cek apakah kolom driver_id ada
            if (!Schema::hasColumn('orders', 'driver_id')) {
                Log::error('Column driver_id does not exist in orders table');
                return view('drivers.ready-orders', ['orders' => collect()]);
            }

            // Ambil pesanan dengan status "shipped" (siap diantar)
            $orders = Order::with(['user', 'address', 'menus'])
                          ->where('status', 'shipped')
                          ->whereNull('driver_id') // Belum diambil driver
                          ->orderBy('created_at', 'asc')
                          ->get();

            Log::info('Ready orders fetched', [
                'count' => $orders->count(),
                'user_id' => Auth::id()
            ]);

            return view('drivers.ready-orders', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching ready orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return view('drivers.ready-orders', ['orders' => collect()]);
        }
    }

    public function processingOrders()
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return view('drivers.processing-orders', ['orders' => collect()]);
            }

            // Cek apakah kolom driver_id ada
            if (!Schema::hasColumn('orders', 'driver_id')) {
                Log::error('Column driver_id does not exist in orders table');
                return view('drivers.processing-orders', ['orders' => collect()]);
            }

            // Orders yang sedang diproses oleh driver ini - gunakan status 'delivery' yang lebih pendek
            $orders = Order::with(['user', 'address', 'menus'])
                          ->where('driver_id', $driver->id)
                          ->whereIn('status', ['delivery']) // Ubah dari 'on_delivery' ke 'delivery'
                          ->orderBy('created_at', 'desc')
                          ->get();

            return view('drivers.processing-orders', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching processing orders', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return view('drivers.processing-orders', ['orders' => collect()]);
        }
    }

    public function deliveryHistory()
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return view('drivers.delivery-history', ['orders' => collect()]);
            }

            // Cek apakah kolom driver_id ada
            if (!Schema::hasColumn('orders', 'driver_id')) {
                Log::error('Column driver_id does not exist in orders table');
                return view('drivers.delivery-history', ['orders' => collect()]);
            }

            // History pengantaran yang sudah selesai
            $orders = Order::with(['user', 'address', 'menus'])
                          ->where('driver_id', $driver->id)
                          ->whereIn('status', ['delivered', 'completed'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

            return view('drivers.delivery-history', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching delivery history', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return view('drivers.delivery-history', ['orders' => collect()]);
        }
    }

    public function takeOrder(Request $request, $orderId)
    {
        try {
            // Cek apakah kolom driver_id ada
            if (!Schema::hasColumn('orders', 'driver_id')) {
                Log::error('Column driver_id does not exist in orders table');
                return response()->json([
                    'success' => false,
                    'message' => 'Database belum siap. Kolom driver_id tidak ditemukan. Silakan jalankan migration.'
                ], 500);
            }

            $user = Auth::user();
            
            Log::info('Driver attempting to take order', [
                'user_id' => $user->id,
                'order_id' => $orderId
            ]);
            
            // Cari driver berdasarkan user_id
            $driver = Driver::where('user_id', $user->id)->first();

            if (!$driver) {
                Log::warning('Driver profile not found', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Profil driver tidak ditemukan. Silakan hubungi administrator.'
                ], 404);
            }

            Log::info('Driver found', [
                'driver_id' => $driver->id,
                'driver_status' => $driver->status
            ]);

            // Cek status driver
            if ($driver->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status driver tidak aktif. Silakan hubungi administrator.'
                ], 400);
            }

            DB::beginTransaction();

            // Cari order yang tersedia
            $order = Order::where('id', $orderId)
                         ->where('status', 'shipped')
                         ->whereNull('driver_id')
                         ->lockForUpdate()
                         ->first();

            if (!$order) {
                DB::rollback();
                Log::warning('Order not available', [
                    'order_id' => $orderId,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak tersedia atau sudah diambil driver lain'
                ], 404);
            }

            Log::info('Order found, updating...', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'current_driver_id' => $order->driver_id
            ]);

            // Update order dengan driver dan ubah status - gunakan 'delivery' yang lebih pendek
            $updated = $order->update([
                'driver_id' => $driver->id,
                'status' => 'delivery' // Ubah dari 'on_delivery' ke 'delivery'
            ]);

            if (!$updated) {
                DB::rollback();
                Log::error('Failed to update order', [
                    'order_id' => $orderId,
                    'driver_id' => $driver->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate pesanan'
                ], 500);
            }

            // Refresh order untuk memastikan update berhasil
            $order->refresh();

            DB::commit();

            Log::info('Driver took order successfully', [
                'driver_id' => $driver->id,
                'order_id' => $orderId,
                'user_id' => $user->id,
                'order_invoice' => $order->invoice,
                'new_status' => $order->status,
                'new_driver_id' => $order->driver_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diambil! Silakan menuju ke lokasi pengantaran.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error taking order', [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeDelivery(Request $request, $orderId)
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil driver tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();

            $order = Order::where('id', $orderId)
                         ->where('driver_id', $driver->id)
                         ->where('status', 'delivery') // Ubah dari 'on_delivery' ke 'delivery'
                         ->lockForUpdate()
                         ->first();

            if (!$order) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau tidak dalam status pengantaran'
                ], 404);
            }

            // Update status order menjadi delivered
            $updated = $order->update([
                'status' => 'delivered'
            ]);

            if (!$updated) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyelesaikan pengantaran'
                ], 500);
            }

            // Increment total deliveries driver
            $driver->increment('total_deliveries');

            DB::commit();

            Log::info('Driver completed delivery successfully', [
                'driver_id' => $driver->id,
                'order_id' => $orderId,
                'user_id' => $user->id,
                'order_invoice' => $order->invoice
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengantaran berhasil diselesaikan!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error completing delivery', [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyelesaikan pengantaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
