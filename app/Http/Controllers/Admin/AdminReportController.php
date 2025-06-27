<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        // Set default date range (last 30 days)
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Validate and parse dates
        try {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } catch (\Exception $e) {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        // Get summary data
        $summary = $this->getSummaryData($startDate, $endDate);
        
        // Get detailed orders
        $orders = $this->getDetailedOrders($startDate, $endDate, $request);
        
        // Get chart data for visualization
        $chartData = $this->getChartData($startDate, $endDate);

        return view('admin.financial-reports', compact(
            'summary', 
            'orders', 
            'startDate', 
            'endDate',
            'chartData'
        ));
    }

    private function getSummaryData($startDate, $endDate)
    {
        $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['delivered', 'completed'])
            ->get();

        $totalRevenue = $completedOrders->sum(function($order) {
            return $order->subtotal + $order->shipping_fee + $order->admin_fee;
        });

        $totalOrders = $completedOrders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Additional metrics
        $totalDeliveryFee = $completedOrders->sum('shipping_fee');
        $totalAdminFee = $completedOrders->sum('admin_fee');
        $totalSubtotal = $completedOrders->sum('subtotal');

        // Popular items
        $popularItems = DB::table('menu_order')
            ->join('orders', 'menu_order.order_id', '=', 'orders.id')
            ->join('menus', 'menu_order.menu_id', '=', 'menus.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->whereIn('orders.status', ['delivered', 'completed'])
            ->select('menus.name', DB::raw('SUM(menu_order.quantity) as total_quantity'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Customer statistics
        $totalCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['delivered', 'completed'])
            ->distinct('user_id')
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'total_delivery_fee' => $totalDeliveryFee,
            'total_admin_fee' => $totalAdminFee,
            'total_subtotal' => $totalSubtotal,
            'popular_items' => $popularItems,
            'total_customers' => $totalCustomers,
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')
        ];
    }

    private function getDetailedOrders($startDate, $endDate, $request)
    {
        $query = Order::with(['user', 'menus', 'address'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by status if requested
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method if requested
        if ($request->has('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by customer name or invoice
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('fullName', 'like', '%' . $search . '%')
                               ->orWhere('username', 'like', '%' . $search . '%');
                  });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    private function getChartData($startDate, $endDate)
    {
        // Daily revenue chart data
        $dailyRevenue = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(subtotal + shipping_fee + admin_fee) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['delivered', 'completed'])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Payment method distribution
        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['delivered', 'completed'])
            ->groupBy('payment_method')
            ->get();

        // Order status distribution
        $orderStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        return [
            'daily_revenue' => $dailyRevenue,
            'payment_methods' => $paymentMethods,
            'order_status' => $orderStatus
        ];
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->subDays(30)))->startOfDay();
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()))->endOfDay();

        $orders = Order::with(['user', 'menus', 'address'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'excel') {
            return $this->exportToExcel($orders, $startDate, $endDate);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($orders, $startDate, $endDate);
        }

        return redirect()->back()->with('error', 'Format tidak didukung');
    }

    private function exportToExcel($orders, $startDate, $endDate)
    {
        $filename = 'laporan-pesanan-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Tanggal',
                'Invoice',
                'Customer',
                'Email',
                'Menu',
                'Alamat',
                'Metode Pembayaran',
                'Subtotal',
                'Ongkir',
                'Admin Fee',
                'Total',
                'Status'
            ]);

            foreach ($orders as $order) {
                $menuList = $order->menus->map(function($menu) {
                    return $menu->name . ' (x' . $menu->pivot->quantity . ')';
                })->implode(', ');

                $total = $order->subtotal + $order->shipping_fee + $order->admin_fee;

                fputcsv($file, [
                    $order->created_at->format('d/m/Y H:i'),
                    $order->invoice,
                    $order->user->fullName ?? $order->user->username,
                    $order->user->email,
                    $menuList,
                    $order->address->address ?? 'Alamat tidak tersedia',
                    ucfirst($order->payment_method),
                    'Rp ' . number_format($order->subtotal, 0, ',', '.'),
                    'Rp ' . number_format($order->shipping_fee, 0, ',', '.'),
                    'Rp ' . number_format($order->admin_fee, 0, ',', '.'),
                    'Rp ' . number_format($total, 0, ',', '.'),
                    ucfirst($order->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($orders, $startDate, $endDate)
    {
        // Implementasi PDF export bisa menggunakan package seperti DomPDF
        // Untuk sekarang, kita return redirect dengan pesan
        return redirect()->back()->with('info', 'Fitur export PDF akan segera tersedia');
    }

    public function getRevenueData(Request $request)
    {
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->subDays(30)))->startOfDay();
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()))->endOfDay();

        $query = Order::whereIn('status', ['delivered', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        switch ($period) {
            case 'weekly':
                $data = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('WEEK(created_at) as week'),
                    DB::raw('SUM(subtotal + shipping_fee + admin_fee) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get();
                break;

            case 'monthly':
                $data = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(subtotal + shipping_fee + admin_fee) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
                break;

            default: // daily
                $data = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(subtotal + shipping_fee + admin_fee) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period
        ]);
    }
}