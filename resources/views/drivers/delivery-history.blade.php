<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pengantaran - JawDelivery</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        .main-content {
            min-height: calc(100vh - 100px);
            background-color: #f8f9fa;
            padding: 20px;
        }

        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
            font-weight: 700;
        }

        .orders-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 24px;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #28a745;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 20px;
        }

        .order-info {
            flex: 1;
        }

        .order-code {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .customer-name {
            font-size: 16px;
            color: #666;
            margin-bottom: 4px;
        }

        .order-time {
            font-size: 14px;
            color: #999;
        }

        .status-badge {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .address-section {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .address-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .address-text {
            color: #666;
            line-height: 1.5;
        }

        .order-items {
            margin-bottom: 16px;
        }

        .items-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .items-list {
            color: #666;
        }

        .total-amount {
            text-align: right;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #999;
        }

        /* Pagination Styles */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 8px;
        }

        .pagination li {
            display: flex;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        .pagination .active span {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .pagination .disabled span {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .order-header {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    @include('components.header')
    
    <main class="main-content">
        <div class="orders-container">
            <div class="page-header">
                <h1>History Pengantaran</h1>
            </div>

            @if($orders->count() > 0)
                <div class="orders-grid">
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-code">{{ $order->invoice }}</div>
                                    <div class="customer-name">{{ $order->buyer_name }}</div>
                                    <div class="order-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <div class="status-badge">Selesai</div>
                            </div>

                            <div class="address-section">
                                <div class="address-label">Alamat Pengantaran:</div>
                                <div class="address-text">{{ $order->buyer_address }}</div>
                            </div>

                            <div class="order-items">
                                <div class="items-label">Detail Pesanan:</div>
                                <div class="items-list">
                                    @foreach($order->menus as $menu)
                                        <div>{{ $menu->pivot->quantity }}x {{ $menu->name }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="total-amount">
                                Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pagination-wrapper">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <h3>Belum Ada History Pengantaran</h3>
                    <p>Anda belum memiliki history pengantaran yang selesai.</p>
                </div>
            @endif
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}"></script>
</body>
</html>
