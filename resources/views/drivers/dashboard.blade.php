<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - JawDelivery</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        /* Driver Dashboard Styles */
        .main-content {
            min-height: calc(100vh - 100px);
            background-color: #f8f9fa;
            padding: 20px;
        }

        .driver-dashboard {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .driver-welcome {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .driver-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .driver-info p {
            color: #666;
            font-size: 16px;
        }

        .driver-status {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Order Detail Card Styles */
        .order-detail-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .order-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-detail-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .btn-ready-orders {
            background: #059669;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }

        .btn-ready-orders:hover {
            background: #047857;
            color: white;
            text-decoration: none;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .order-info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .order-info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .order-info-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .order-address {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .order-address-label {
            font-size: 12px;
            color: #1976d2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .order-address-text {
            color: #333;
            line-height: 1.5;
        }

        .order-items {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .order-items-label {
            font-size: 12px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-quantity {
            color: #666;
            font-size: 14px;
        }

        .order-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-take-order {
            background: #dc3545;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }

        .btn-take-order:hover {
            background: #c82333;
        }

        .btn-take-order:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .no-order-message {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-size: 16px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .driver-welcome {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .driver-status {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .order-detail-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .btn-ready-orders {
                width: 100%;
                justify-content: center;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
            }

            .btn-take-order {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include('components.header')
    
    <main class="main-content">
        <div class="driver-dashboard">
            <div class="dashboard-header">
                <div class="driver-welcome">
                    <div class="driver-info">
                        <h1>Selamat Datang, {{ Auth::user()->fullName ?? Auth::user()->username }}!</h1>
                        <p>Dashboard Driver - JawDelivery</p>
                    </div>
                    <div class="driver-status">
                        <span class="status-badge {{ $stats['status'] === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $stats['status'] === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['total_deliveries'] }}</div>
                        <div class="stat-label">Total Pengantaran</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            {{ $stats['is_available'] ? 'Tersedia' : 'Tidak Tersedia' }}
                        </div>
                        <div class="stat-label">Status Ketersediaan</div>
                    </div>
                </div>
            </div>
            
            <!-- Order Detail Section -->
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h2 class="order-detail-title">🚚 Pesanan Siap Diantar</h2>
                    <a href="{{ route('driver.ready-orders') }}" class="btn-ready-orders">
                        📋 Lihat Semua Pesanan
                    </a>
                </div>

                <div id="alertContainer"></div>

                @if(isset($readyOrder) && $readyOrder)
                    <div id="order-detail-{{ $readyOrder->id }}">
                        <div class="order-info-grid">
                            <div class="order-info-item">
                                <div class="order-info-label">Kode Pesanan</div>
                                <div class="order-info-value">{{ $readyOrder->invoice }}</div>
                            </div>
                            <div class="order-info-item">
                                <div class="order-info-label">Nama Pelanggan</div>
                                <div class="order-info-value">{{ $readyOrder->buyer_name }}</div>
                            </div>
                        </div>

                        <div class="order-address">
                            <div class="order-address-label">📍 Alamat Pengantaran</div>
                            <div class="order-address-text">{{ $readyOrder->buyer_address }}</div>
                        </div>

                        <div class="order-items">
                            <div class="order-items-label">🍽️ Detail Pesanan</div>
                            @foreach($readyOrder->menus as $menu)
                                <div class="order-item">
                                    <span class="item-name">{{ $menu->name }}</span>
                                    <span class="item-quantity">{{ $menu->pivot->quantity }}x</span>
                                </div>
                            @endforeach
                            <div class="order-item" style="border-top: 2px solid #059669; margin-top: 10px; padding-top: 10px; font-weight: 600;">
                                <span>Total</span>
                                <span>Rp {{ number_format($readyOrder->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="order-actions">
                            <button class="btn-take-order" onclick="takeOrderFromDashboard({{ $readyOrder->id }})">
                                🚀 Ambil Pesanan Ini
                            </button>
                        </div>
                    </div>
                @else
                    <div class="no-order-message">
                        <p>🎉 Tidak ada pesanan yang siap diantar saat ini.</p>
                        <p>Silakan periksa kembali nanti atau klik tombol di atas untuk melihat semua pesanan.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}"></script>
    <script>
        function takeOrderFromDashboard(orderId) {
            const button = document.querySelector('.btn-take-order');
            const originalText = button.textContent;
            
            // Disable button dan ubah text
            button.disabled = true;
            button.textContent = '⏳ Mengambil...';
            
            console.log('Taking order from dashboard:', orderId);
            
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
                    
                    // Hide order detail and show success message
                    const orderDetail = document.getElementById(`order-detail-${orderId}`);
                    orderDetail.style.transition = 'all 0.3s ease';
                    orderDetail.style.opacity = '0';
                    
                    setTimeout(() => {
                        orderDetail.innerHTML = `
                            <div class="no-order-message">
                                <p>✅ Pesanan berhasil diambil!</p>
                                <p>Silakan menuju ke halaman "Pesanan Diproses" untuk melanjutkan pengantaran.</p>
                                <div style="margin-top: 15px;">
                                    <a href="{{ route('driver.processing-orders') }}" class="btn-ready-orders">
                                        🚛 Lihat Pesanan Diproses
                                    </a>
                                </div>
                            </div>
                        `;
                        orderDetail.style.opacity = '1';
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
    </script>
</body>
</html>
