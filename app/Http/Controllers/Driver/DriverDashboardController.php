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
        try {
            $user = Auth::user();
            
            // Cek apakah user sudah login
            if (!$user) {
                return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Cari driver berdasarkan user_id
            $driver = Driver::where('user_id', $user->id)->first();
            
            // Jika driver tidak ditemukan, buat default stats
            if (!$driver) {
                Log::warning('Driver profile not found for user', ['user_id' => $user->id]);
                
                $stats = [
                    'total_deliveries' => 0,
                    'status' => 'inactive',
                    'is_available' => false
                ];
                
                $readyOrder = null;
                
                return view('drivers.dashboard', compact('stats', 'readyOrder'))
                    ->with('warning', 'Profil driver tidak ditemukan. Silakan hubungi administrator.');
            }

            // Get driver statistics
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
                } else {
                    Log::warning('Column driver_id does not exist in orders table');
                }
            } catch (\Exception $e) {
                Log::error('Error fetching ready order for dashboard', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }

            return view('drivers.dashboard', compact('driver', 'stats', 'readyOrder'));
            
        } catch (\Exception $e) {
            Log::error('Error in driver dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            // Return dengan default stats jika terjadi error
            $stats = [
                'total_deliveries' => 0,
                'status' => 'inactive',
                'is_available' => false
            ];
            
            $readyOrder = null;
            
            return view('drivers.dashboard', compact('stats', 'readyOrder'))
                ->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }

    public function profile()
    {
        try {
            $user = Auth::user();
            $driver = Driver::where('user_id', $user->id)->first();
            
            if (!$driver) {
                return redirect()->route('driver.dashboard')
                    ->with('error', 'Profil driver tidak ditemukan');
            }
            
            return view('drivers.profile', compact('driver'));
            
        } catch (\Exception $e) {
            Log::error('Error in driver profile', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('driver.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat profil');
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
