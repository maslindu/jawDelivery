<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return redirect('/dashboard')->with('error', 'Driver profile not found');
        }

        // Get driver statistics
        $stats = [
            'total_deliveries' => $driver->total_deliveries ?? 0,
            'rating' => $driver->rating ?? 0,
            'status' => $driver->status ?? 'inactive',
            'is_available' => $driver->is_available ?? false
        ];

        return view('drivers.dashboard', compact('driver', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        return view('drivers.profile', compact('driver'));
    }

    public function readyOrders()
    {
        // Orders yang siap diantar (status: confirmed atau ready_for_pickup)
        $orders = Order::whereIn('status', ['confirmed', 'ready_for_pickup'])
                      ->with(['user', 'orderItems.menu'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('drivers.ready-orders', compact('orders'));
    }

    public function processingOrders()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        // Orders yang sedang diproses oleh driver ini
        $orders = Order::where('driver_id', $driver->id ?? 0)
                      ->whereIn('status', ['picked_up', 'on_delivery'])
                      ->with(['user', 'orderItems.menu'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('drivers.processing-orders', compact('orders'));
    }

    public function deliveryHistory()
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        // History pengantaran yang sudah selesai
        $orders = Order::where('driver_id', $driver->id ?? 0)
                      ->whereIn('status', ['delivered', 'completed'])
                      ->with(['user', 'orderItems.menu'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('drivers.delivery-history', compact('orders'));
    }

    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if ($driver) {
            $driver->toggleAvailability();
            return response()->json([
                'success' => true,
                'is_available' => $driver->is_available,
                'message' => $driver->is_available ? 'Status berubah menjadi tersedia' : 'Status berubah menjadi tidak tersedia'
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
    }
}
