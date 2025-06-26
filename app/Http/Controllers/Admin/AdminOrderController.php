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

    // ... rest of the methods remain the same
}
