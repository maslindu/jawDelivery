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
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .order-header {
            display: flex;
            justify-content: between;
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

        .order-actions {
            display: flex;
            gap: 12px;
        }

        .btn-take-order {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-take-order:hover {
            background: #218838;
        }

        .btn-take-order:disabled {
            background: #6c757d;
            cursor: not-allowed;
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

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .order-header {
                flex-direction: column;
                gap: 16px;
            }
            
            .order-actions {
                width: 100%;
                justify-content: stretch;
            }
            
            .btn-take-order {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    @include('components.header')
    
    <main class="main-content">
        <div class="orders-container">
            <div class="page-header">
                <h1>Pesanan Siap Diantar</h1>
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
                                <div class="order-actions">
                                    <button class="btn-take-order" onclick="takeOrder({{ $order->id }})">
                                        Ambil Pesanan
                                    </button>
                                </div>
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
            @else
                <div class="empty-state">
                    <h3>Tidak Ada Pesanan Siap Diantar</h3>
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
            
            fetch(`/driver/take-order/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    // Remove order card dari tampilan
                    const orderCard = document.getElementById(`order-${orderId}`);
                    orderCard.style.transition = 'opacity 0.5s';
                    orderCard.style.opacity = '0';
                    
                    setTimeout(() => {
                        orderCard.remove();
                        
                        // Check if no more orders
                        const remainingOrders = document.querySelectorAll('.order-card');
                        if (remainingOrders.length === 0) {
                            location.reload();
                        }
                    }, 500);
                } else {
                    showAlert(data.message, 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengambil pesanan', 'error');
                button.disabled = false;
                button.textContent = originalText;
            });
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            
            // Remove existing alerts
            alertContainer.innerHTML = '';
            
            // Create new alert
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    </script>
</body>
</html>
