<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return redirect('/dashboard')->with('error', 'Driver profile not found');
        }

        // Get driver statistics (menghapus rating)
        $stats = [
            'total_deliveries' => $driver->total_deliveries ?? 0,
            'status' => $driver->status ?? 'inactive',
            'is_available' => $driver->is_available ?? false
        ];

        // Get one ready order for dashboard display
        $readyOrder = null;
        try {
            if (Schema::hasColumn('orders', 'driver_id')) {
                $readyOrder = Order::with(['user', 'address', 'menus'])
                                  ->where('status', Order::STATUS_SHIPPED)
                                  ->whereNull('driver_id')
                                  ->orderBy('created_at', 'asc')
                                  ->first();
                
                Log::info('Ready order fetched for dashboard', [
                    'order_found' => $readyOrder ? true : false,
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching ready order for dashboard', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }

        return view('drivers.dashboard', compact('driver', 'stats', 'readyOrder'));
    }

    public function profile()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        return view('drivers.profile', compact('driver'));
    }

    public function readyOrders()
    {
        try {
            // Cek apakah kolom driver_id ada
            if (!Schema::hasColumn('orders', 'driver_id')) {
                Log::error('Column driver_id does not exist in orders table');
                return view('drivers.ready-orders', ['orders' => collect()]);
            }

            // Ambil pesanan dengan status "shipped" (siap diantar) yang belum diambil driver
            $orders = Order::with(['user', 'address', 'menus'])
                          ->where('status', Order::STATUS_SHIPPED)
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

            // Orders yang sedang diproses oleh driver ini - status 'shipped' dengan driver_id
            $orders = Order::with(['user', 'address', 'menus'])
                          ->where('driver_id', $driver->id)
                          ->where('status', Order::STATUS_SHIPPED) // Masih shipped tapi sudah ada driver_id
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
                          ->whereIn('status', [Order::STATUS_DELIVERED])
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

    public function toggleAvailability(Request $request)
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Driver profile not found'
                ], 404);
            }
            
            // Toggle availability
            $driver->is_available = !$driver->is_available;
            $driver->save();
            
            Log::info('Driver availability toggled', [
                'driver_id' => $driver->id,
                'user_id' => $user->id,
                'new_availability' => $driver->is_available
            ]);
            
            return response()->json([
                'success' => true,
                'is_available' => $driver->is_available,
                'message' => $driver->is_available ? 'Status berubah menjadi tersedia' : 'Status berubah menjadi tidak tersedia'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling driver availability', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive'
            ]);

            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Driver profile not found'
                ], 404);
            }
            
            $driver->status = $request->status;
            $driver->save();
            
            Log::info('Driver status updated', [
                'driver_id' => $driver->id,
                'user_id' => $user->id,
                'new_status' => $driver->status
            ]);
            
            return response()->json([
                'success' => true,
                'status' => $driver->status,
                'message' => 'Status driver berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating driver status', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Driver profile not found'
                ], 404);
            }

            $stats = [
                'total_deliveries' => $driver->total_deliveries ?? 0,
                'status' => $driver->status ?? 'inactive',
                'is_available' => $driver->is_available ?? false
            ];

            // Get ready orders count
            $readyOrdersCount = 0;
            if (Schema::hasColumn('orders', 'driver_id')) {
                $readyOrdersCount = Order::where('status', Order::STATUS_SHIPPED)
                                        ->whereNull('driver_id')
                                        ->count();
            }

            $stats['ready_orders_count'] = $readyOrdersCount;

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting driver stats', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}
