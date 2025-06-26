<?php
// app/Http/Controllers/Admin/AdminOrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Get all orders with relationships including address
        $orders = Order::with(['user', 'menus', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count orders by status
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders', compact('orders', 'statusCounts'));
    }

    // Method baru untuk halaman detail order
    public function detail($id)
    {
        try {
            $order = Order::with(['user', 'menus', 'address'])->findOrFail($id);
            
            return view('admin.orders-detail', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders')->with('error', 'Pesanan tidak ditemukan');
        }
    }

    // Method untuk update status order
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
            ]);

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Update status order
            $order->update(['status' => $newStatus]);

            // Get updated status counts
            $statusCounts = [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'invoice' => $order->invoice
                ],
                'statusCounts' => $statusCounts,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mendapatkan status counts
    public function getStatusCounts()
    {
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return response()->json($statusCounts);
    }

    // Method untuk mendapatkan detail order
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'menus', 'address'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
    }
}
