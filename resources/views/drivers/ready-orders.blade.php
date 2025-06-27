<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Siap Diantar - JawDelivery</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        .main-content {
            min-height: calc(100vh - 100px);
            background-color: #f8fafc;
            padding: 20px;
        }

        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #2563eb;
        }

        .page-header h1 {
            margin: 0;
            color: #1e293b;
            font-size: 24px;
            font-weight: 600;
        }

        .stats-bar {
            display: flex;
            gap: 16px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .stat-item {
            background: #2563eb;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-align: center;
            min-width: 100px;
        }

        .stat-number {
            font-size: 18px;
            font-weight: 600;
            display: block;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }

        .orders-grid {
            display: grid;
            gap: 16px;
        }

        .order-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #059669;
            transition: all 0.2s ease;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .customer-name {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .order-time {
            font-size: 12px;
            color: #94a3b8;
        }

        .btn-take-order {
            background: #059669;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-take-order:hover {
            background: #047857;
        }

        .btn-take-order:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .address-section {
            background: #f1f5f9;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 12px;
        }

        .address-label {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 13px;
        }

        .address-text {
            color: #475569;
            font-size: 13px;
            line-height: 1.4;
        }

        .order-items {
            margin-bottom: 12px;
        }

        .items-label {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 13px;
        }

        .items-list {
            color: #475569;
            background: #f1f5f9;
            padding: 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        .items-list div {
            padding: 2px 0;
        }

        .total-amount {
            text-align: right;
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .empty-state h3 {
            color: #64748b;
            margin-bottom: 8px;
            font-size: 18px;
            font-weight: 500;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 14px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            
            .order-header {
                flex-direction: column;
                gap: 12px;
            }
            
            .btn-take-order {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @include('components.header')
    
    <main class="main-content">
        <div class="orders-container">
            <div class="page-header">
                <h1>🚚 Pesanan Siap Diantar</h1>
                <div class="stats-bar">
                    <div class="stat-item">
                        <span class="stat-number">{{ $orders->count() }}</span>
                        <span class="stat-label">Tersedia</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $orders->sum('total_amount') ? number_format($orders->sum('total_amount'), 0, ',', '.') : '0' }}</span>
                        <span class="stat-label">Total (Rp)</span>
                    </div>
                </div>
            </div>

            <div id="alertContainer"></div>

            @if($orders->count() > 0)
                <div class="orders-grid">
                    @foreach($orders as $order)
                        <div class="order-card" id="order-{{ $order->id }}">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-code">{{ $order->invoice }}</div>
                                    <div class="customer-name">{{ $order->buyer_name }}</div>
                                    <div class="order-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <div>
                                    <button class="btn-take-order" onclick="takeOrder({{ $order->id }})">
                                        Ambil Pesanan
                                    </button>
                                </div>
                            </div>

                            <div class="address-section">
                                <div class="address-label">📍 Alamat Pengantaran</div>
                                <div class="address-text">{{ $order->buyer_address }}</div>
                            </div>

                            <div class="order-items">
                                <div class="items-label">🍽️ Detail Pesanan</div>
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
            @else
                <div class="empty-state">
                    <h3>📦 Tidak Ada Pesanan Siap Diantar</h3>
                    <p>Saat ini tidak ada pesanan yang siap untuk diantar.</p>
                </div>
            @endif
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}"></script>
    <script>
        function takeOrder(orderId) {
            const button = document.querySelector(`#order-${orderId} .btn-take-order`);
            const originalText = button.textContent;
            
            // Disable button dan ubah text
            button.disabled = true;
            button.textContent = 'Mengambil...';
            
            console.log('Taking order:', orderId);
            
            fetch(`/driver/take-order/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    // Remove order card
                    const orderCard = document.getElementById(`order-${orderId}`);
                    orderCard.style.transition = 'all 0.3s ease';
                    orderCard.style.opacity = '0';
                    orderCard.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        orderCard.remove();
                        updateStats();
                        
                        // Reload if no more orders
                        const remainingOrders = document.querySelectorAll('.order-card');
                        if (remainingOrders.length === 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }, 300);
                } else {
                    showAlert(data.message || 'Terjadi kesalahan saat mengambil pesanan', 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showAlert('Terjadi kesalahan saat mengambil pesanan: ' + error.message, 'error');
                button.disabled = false;
                button.textContent = originalText;
            });
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = '';
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }

        function updateStats() {
            const remainingOrders = document.querySelectorAll('.order-card');
            const statNumber = document.querySelector('.stat-number');
            if (statNumber) {
                statNumber.textContent = remainingOrders.length;
            }
        }
    </script>
</body>
</html>
