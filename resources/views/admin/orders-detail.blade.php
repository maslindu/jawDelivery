<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Detail Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders-detail.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="back-button-container">
            <button class="back-button">
                <span class="back-icon">←</span>
                <span>Kembali ke Daftar Pesanan</span>
            </button>
        </div>

        <div class="details-container">
            <!-- Order Section -->
            <div class="order-section">
                <div class="section-card">
                    <h2 class="section-title">📋 Detail Pesanan</h2>
                    
                    <div class="order-header">
                        <div class="invoice-container">
                            <h3 class="invoice-number">{{ $order->invoice ?? 'N/A' }}</h3>
                            <div class="order-meta">
                                <span class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-items">
                        @if ($order->menus && $order->menus->count() > 0)
                            @foreach ($order->menus as $menu)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ $menu->image_url }}" 
                                             alt="{{ $menu->name }}" 
                                             class="menu-image"
                                             onerror="this.src='{{ asset('storage/menu/default-image.jpg') }}'">
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">{{ $menu->name }}</div>
                                        <div class="item-description">{{ $menu->description ?? 'Tidak ada deskripsi' }}</div>
                                        <div class="item-price-info">
                                            <span class="unit-price">Rp {{ number_format($menu->price, 0, ',', '.') }} × {{ $menu->pivot->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="item-quantity">
                                        <span class="quantity-badge">{{ $menu->pivot->quantity }}</span>
                                    </div>
                                    <div class="item-total">
                                        <span class="subtotal">Rp {{ number_format($menu->price * $menu->pivot->quantity, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-items">
                                <div class="empty-icon">🍽️</div>
                                <div class="empty-text">Tidak ada menu dalam pesanan ini</div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Ongkos Kirim:</span>
                            <span class="summary-value">Rp {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Biaya Admin:</span>
                            <span class="summary-value">Rp {{ number_format($order->admin_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row payment-method-row">
                            <span class="summary-label">Metode Pembayaran:</span>
                            <span class="payment-method">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                        </div>
                        <div class="summary-row total-row">
                            <span class="summary-label">Total Pembayaran:</span>
                            <span class="total-amount">Rp {{ number_format(($order->subtotal ?? 0) + ($order->shipping_fee ?? 0) + ($order->admin_fee ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="order-notes">
                            <div class="notes-title">📝 Catatan Pesanan</div>
                            <div class="notes-content">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status & Customer Section -->
            <div class="sidebar-section">
                <!-- Current Status Display -->
                <div class="section-card status-display-card">
                    <h3 class="section-subtitle">📊 Status Saat Ini</h3>
                    <div class="current-status-display">
                        <div class="status-icon-large">
                            @switch($order->status)
                                @case('pending')
                                    ⏳
                                    @break
                                @case('processing')
                                    🔄
                                    @break
                                @case('shipped')
                                    🚚
                                    @break
                                @case('delivered')
                                    ✅
                                    @break
                                @case('cancelled')
                                    ❌
                                    @break
                                @default
                                    📦
                            @endswitch
                        </div>
                        <div class="status-info">
                            <div class="status-title">
                                @switch($order->status)
                                    @case('pending')
                                        Menunggu Konfirmasi
                                        @break
                                    @case('processing')
                                        Sedang Diproses
                                        @break
                                    @case('shipped')
                                        Sedang Dikirim
                                        @break
                                    @case('delivered')
                                        Pesanan Selesai
                                        @break
                                    @case('cancelled')
                                        Pesanan Dibatalkan
                                        @break
                                    @default
                                        Status Tidak Diketahui
                                @endswitch
                            </div>
                            <div class="status-time">Diperbarui: {{ $order->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Progress Steps -->
                    <div class="status-progress">
                        <div class="progress-step {{ $order->status == 'pending' ? 'active' : ($order->status != 'cancelled' && in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '') }}">
                            <div class="step-icon">⏳</div>
                            <div class="step-label">Menunggu</div>
                        </div>
                        <div class="progress-step {{ $order->status == 'processing' ? 'active' : ($order->status != 'cancelled' && in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '') }}">
                            <div class="step-icon">🔄</div>
                            <div class="step-label">Diproses</div>
                        </div>
                        <div class="progress-step {{ $order->status == 'shipped' ? 'active' : ($order->status == 'delivered' ? 'completed' : '') }}">
                            <div class="step-icon">🚚</div>
                            <div class="step-label">Dikirim</div>
                        </div>
                        <div class="progress-step {{ $order->status == 'delivered' ? 'active completed' : '' }}">
                            <div class="step-icon">✅</div>
                            <div class="step-label">Selesai</div>
                        </div>
                    </div>
                </div>

                <!-- Status Actions -->
                <div class="section-card status-actions-card">
                    <h3 class="section-subtitle">🔄 Ubah Status Pesanan</h3>
                    <div class="status-actions">
                        <div class="status-buttons">
                            <button class="status-btn {{ $order->status == 'pending' ? 'active' : '' }}" 
                                    data-status="pending">
                                <div class="btn-icon">⏳</div>
                                <div class="btn-text">Menunggu</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'processing' ? 'active' : '' }}" 
                                    data-status="processing">
                                <div class="btn-icon">🔄</div>
                                <div class="btn-text">Diproses</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'shipped' ? 'active' : '' }}" 
                                    data-status="shipped">
                                <div class="btn-icon">🚚</div>
                                <div class="btn-text">Dikirim</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'delivered' ? 'active' : '' }}" 
                                    data-status="delivered">
                                <div class="btn-icon">✅</div>
                                <div class="btn-text">Selesai</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'cancelled' ? 'active' : '' }}" 
                                    data-status="cancelled">
                                <div class="btn-icon">❌</div>
                                <div class="btn-text">Dibatalkan</div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="section-card customer-info-card">
                    <h3 class="section-subtitle">👤 Informasi Pelanggan</h3>
                    <div class="customer-info">
                        <div class="customer-detail">
                            <div class="detail-icon">👤</div>
                            <div class="detail-content">
                                <div class="detail-label">Nama</div>
                                <div class="detail-value">{{ $order->user->name ?? $order->user->username ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        @if ($order->user && $order->user->phone)
                            <div class="customer-detail">
                                <div class="detail-icon">📞</div>
                                <div class="detail-content">
                                    <div class="detail-label">Telepon</div>
                                    <div class="detail-value">{{ $order->user->phone }}</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="customer-detail">
                            <div class="detail-icon">📍</div>
                            <div class="detail-content">
                                <div class="detail-label">Alamat</div>
                                <div class="detail-value">{{ $order->address->address ?? 'Alamat tidak tersedia' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Notification Container -->
    <div id="notification" class="notification"></div>

    <!-- Pass order data to JavaScript -->
    <script>
        window.orderData = {
            id: {{ $order->id }},
            status: '{{ $order->status }}'
        };
    </script>

    <!-- Load external JavaScript files -->
    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/admin-orders-detail.js') }}" defer></script>
</body>

</html>
