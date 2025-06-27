<?php
// app/Http/Controllers/Admin/AdminOrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Get all orders with relationships including address and driver
        $orders = Order::with(['user', 'menus', 'address', 'driver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count orders by status - gunakan konstanta dari model
        $statusCounts = [
            Order::STATUS_PENDING => Order::where('status', Order::STATUS_PENDING)->count(),
            Order::STATUS_PROCESSING => Order::where('status', Order::STATUS_PROCESSING)->count(),
            Order::STATUS_SHIPPED => Order::where('status', Order::STATUS_SHIPPED)->count(),
            Order::STATUS_DELIVERED => Order::where('status', Order::STATUS_DELIVERED)->count(),
            Order::STATUS_CANCELLED => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders', compact('orders', 'statusCounts'));
    }

    // Method baru untuk halaman detail order
    public function detail($id)
    {
        try {
            $order = Order::with(['user', 'menus', 'address', 'driver'])->findOrFail($id);
            
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
                'status' => 'required|in:' . implode(',', array_keys(Order::getStatusOptions()))
            ]);

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Validasi perubahan status
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perubahan status tidak valid dari ' . $oldStatus . ' ke ' . $newStatus
                ], 400);
            }

            // Update status order
            $order->update(['status' => $newStatus]);

            // Log perubahan status
            Log::info('Order status updated', [
                'order_id' => $order->id,
                'invoice' => $order->invoice,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => auth()->id()
            ]);

            // Get updated status counts
            $statusCounts = [
                Order::STATUS_PENDING => Order::where('status', Order::STATUS_PENDING)->count(),
                Order::STATUS_PROCESSING => Order::where('status', Order::STATUS_PROCESSING)->count(),
                Order::STATUS_SHIPPED => Order::where('status', Order::STATUS_SHIPPED)->count(),
                Order::STATUS_DELIVERED => Order::where('status', Order::STATUS_DELIVERED)->count(),
                Order::STATUS_CANCELLED => Order::where('status', Order::STATUS_CANCELLED)->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui dari ' . Order::getStatusOptions()[$oldStatus] . ' ke ' . Order::getStatusOptions()[$newStatus],
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'status_label' => Order::getStatusOptions()[$order->status],
                    'invoice' => $order->invoice
                ],
                'statusCounts' => $statusCounts,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating order status', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk validasi transisi status
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        // Definisi transisi status yang valid
        $validTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPED => [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED],
            Order::STATUS_DELIVERED => [], // Status final
            Order::STATUS_CANCELLED => [], // Status final
        ];

        // Jika status sama, selalu valid (tidak ada perubahan)
        if ($oldStatus === $newStatus) {
            return true;
        }

        // Cek apakah transisi valid
        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    // Method untuk mendapatkan status counts
    public function getStatusCounts()
    {
        $statusCounts = [
            Order::STATUS_PENDING => Order::where('status', Order::STATUS_PENDING)->count(),
            Order::STATUS_PROCESSING => Order::where('status', Order::STATUS_PROCESSING)->count(),
            Order::STATUS_SHIPPED => Order::where('status', Order::STATUS_SHIPPED)->count(),
            Order::STATUS_DELIVERED => Order::where('status', Order::STATUS_DELIVERED)->count(),
            Order::STATUS_CANCELLED => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return response()->json($statusCounts);
    }

    // Method untuk mendapatkan detail order
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'menus', 'address', 'driver'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'invoice' => $order->invoice,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'buyer_name' => $order->buyer_name,
                    'buyer_address' => $order->buyer_address,
                    'total_amount' => $order->total_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                    'driver' => $order->driver ? [
                        'id' => $order->driver->id,
                        'name' => $order->driver->user->name ?? $order->driver->user->username,
                    ] : null,
                    'menus' => $order->menus->map(function($menu) {
                        return [
                            'name' => $menu->name,
                            'quantity' => $menu->pivot->quantity,
                            'price' => $menu->price
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
    }
}
