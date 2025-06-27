<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Laporan Keuangan</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/financial-reports.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <!-- Page Header -->
        <header class="page-header">
            <h1 class="page-title">Laporan Keuangan</h1>
            <p class="page-subtitle">Kelola dan pantau performa bisnis Anda</p>
        </header>

        <!-- Filter Section -->
        <section class="filter-card">
            <div class="filter-header">
                <h2>Filter Laporan</h2>
                <div class="filter-icon">📊</div>
            </div>
            <form method="GET" action="{{ route('admin.financial-reports') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" 
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" 
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status Pesanan</label>
                        <select id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method">
                            <option value="">Semua Metode</option>
                            <option value="Transfer Bank" {{ request('payment_method') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ request('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="GoPay" {{ request('payment_method') === 'GoPay' ? 'selected' : '' }}>GoPay</option>
                            <option value="ShopeePay" {{ request('payment_method') === 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                            <option value="DANA" {{ request('payment_method') === 'DANA' ? 'selected' : '' }}>DANA</option>
                            <option value="Bayar Tunai (COD)" {{ request('payment_method') === 'Bayar Tunai (COD)' ? 'selected' : '' }}>COD</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="search">Cari (Invoice/Customer)</label>
                        <input type="text" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan invoice atau nama customer">
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.financial-reports') }}" class="btn-secondary">Reset</a>
                    <button type="submit" class="btn-primary">Filter Laporan</button>
                </div>
            </form>
        </section>

        @if(session('success'))
            <div class="alert alert-success" style="
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                padding: 16px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                <span style="font-size: 20px;">✅</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 16px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                <span style="font-size: 20px;">❌</span>
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info" style="
                background-color: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
                padding: 16px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                <span style="font-size: 20px;">ℹ️</span>
                {{ session('info') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <section class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon">💰</div>
                <div class="summary-content">
                    <h3>Total Pendapatan</h3>
                    <p class="summary-amount">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                    <span class="summary-period">{{ $summary['period'] }}</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">📦</div>
                <div class="summary-content">
                    <h3>Total Pesanan</h3>
                    <p class="summary-amount">{{ number_format($summary['total_orders']) }}</p>
                    <span class="summary-period">Pesanan selesai</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">📈</div>
                <div class="summary-content">
                    <h3>Rata-rata Pesanan</h3>
                    <p class="summary-amount">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</p>
                    <span class="summary-period">Per pesanan</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">👥</div>
                <div class="summary-content">
                    <h3>Total Customer</h3>
                    <p class="summary-amount">{{ number_format($summary['total_customers']) }}</p>
                    <span class="summary-period">Customer unik</span>
                </div>
            </div>
        </section>

        <!-- Additional Statistics -->
        <section class="summary-grid" style="margin-bottom: 30px;">
            <div class="summary-card">
                <div class="summary-icon">🚚</div>
                <div class="summary-content">
                    <h3>Total Ongkir</h3>
                    <p class="summary-amount">Rp {{ number_format($summary['total_delivery_fee'], 0, ',', '.') }}</p>
                    <span class="summary-period">Biaya pengiriman</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">⚙️</div>
                <div class="summary-content">
                    <h3>Total Admin Fee</h3>
                    <p class="summary-amount">Rp {{ number_format($summary['total_admin_fee'], 0, ',', '.') }}</p>
                    <span class="summary-period">Biaya administrasi</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">🍽️</div>
                <div class="summary-content">
                    <h3>Subtotal Menu</h3>
                    <p class="summary-amount">Rp {{ number_format($summary['total_subtotal'], 0, ',', '.') }}</p>
                    <span class="summary-period">Total penjualan menu</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">🔥</div>
                <div class="summary-content">
                    <h3>Item Terpopuler</h3>
                    <p class="summary-amount">{{ $summary['popular_items']->first()->name ?? 'Tidak ada data' }}</p>
                    <span class="summary-period">
                        {{ $summary['popular_items']->first() ? $summary['popular_items']->first()->total_quantity . ' terjual' : '' }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Popular Items Section -->
        @if($summary['popular_items']->count() > 0)
        <section class="report-card" style="margin-bottom: 30px;">
            <div class="report-header">
                <h2>🔥 Menu Terpopuler</h2>
            </div>
            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Menu</th>
                            <th>Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['popular_items'] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td><strong>{{ $item->total_quantity }} porsi</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @endif

        <!-- Report Table -->
        <section class="report-card">
            <div class="report-header">
                <h2>📋 Detail Pesanan</h2>
                <div class="report-actions">
                    <form method="GET" action="{{ route('admin.reports.export') }}" style="display: inline-block;">
                        <input type="hidden" name="format" value="excel">
                        <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <button type="submit" class="btn-export">📊 Export Excel</button>
                    </form>
                    <form method="GET" action="{{ route('admin.reports.export') }}" style="display: inline-block;">
                        <input type="hidden" name="format" value="pdf">
                        <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <button type="submit" class="btn-export">📄 Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Menu</th>
                            <th>Alamat</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.detail', $order->id) }}" 
                                       style="color: #3b82f6; text-decoration: none;">
                                        {{ $order->invoice }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $order->user->fullName ?? $order->user->username }}</strong><br>
                                    <small>{{ $order->user->email }}</small>
                                </td>
                                <td>
                                    @foreach($order->menus as $menu)
                                        <small>{{ $menu->name }} (x{{ $menu->pivot->quantity }})</small>
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                </td>
                                <td>
                                    <small>{{ Str::limit($order->address->address ?? 'Alamat tidak tersedia', 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge">{{ ucfirst($order->payment_method) }}</span>
                                </td>
                                <td class="amount">
                                    Rp {{ number_format($order->subtotal + $order->shipping_fee + $order->admin_fee, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="status {{ in_array($order->status, ['delivered', 'completed']) ? 'success' : 'pending' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                    Tidak ada data pesanan untuk filter yang dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="pagination" style="margin-top: 20px; display: flex; justify-content: center;">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </section>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/financial-reports.js') }}" defer></script>
    <script>
        // Auto-submit form when dates change
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            
            // Validate date range
            function validateDates() {
                if (startDate.value && endDate.value) {
                    if (new Date(startDate.value) > new Date(endDate.value)) {
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                        startDate.focus();
                        return false;
                    }
                }
                return true;
            }

            startDate.addEventListener('change', validateDates);
            endDate.addEventListener('change', validateDates);

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Export functions
        function exportReport(format) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("admin.reports.export") }}';
            
            // Add all current filter parameters
            const params = {
                format: format,
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                status: document.getElementById('status').value,
                payment_method: document.getElementById('payment_method').value,
                search: document.getElementById('search').value
            };
            
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = params[key];
                    form.appendChild(input);
                }
            });
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            // Show notification
            showNotification(`Export ${format.toUpperCase()} sedang diproses...`, 'info');
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            
            if (type === 'success') {
                notification.style.backgroundColor = '#28a745';
            } else if (type === 'info') {
                notification.style.backgroundColor = '#17a2b8';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#fe4a49';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>