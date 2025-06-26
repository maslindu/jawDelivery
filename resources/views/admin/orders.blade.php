<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Orders Management</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
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

        <!-- Status Pesanan Section -->
        <section class="order-status-section">
            <h2 class="section-title">📊 Status Pesanan</h2>
            <div class="order-status-grid">
                <div class="order-status-card">
                    <div class="order-status-count status-pending" id="count-pending">{{ $statusCounts['pending'] }}
                    </div>
                    <span class="order-status-label">Menunggu</span>
                </div>
                <div class="order-status-card">
                    <div class="order-status-count status-processing" id="count-processing">
                        {{ $statusCounts['processing'] }}</div>
                    <span class="order-status-label">Diproses</span>
                </div>
                <div class="order-status-card">
                    <div class="order-status-count status-shipped" id="count-shipped">{{ $statusCounts['shipped'] }}
                    </div>
                    <span class="order-status-label">Dikirim</span>
                </div>
                <div class="order-status-card">
                    <div class="order-status-count status-delivered" id="count-delivered">
                        {{ $statusCounts['delivered'] }}</div>
                    <span class="order-status-label">Selesai</span>
                </div>
                <div class="order-status-card">
                    <div class="order-status-count status-cancelled" id="count-cancelled">
                        {{ $statusCounts['cancelled'] }}</div>
                    <span class="order-status-label">Dibatalkan</span>
                </div>
            </div>
        </section>

        <!-- Daftar Pesanan Section -->
        <section class="orders-list-section">
            <h2 class="section-title">📋 Daftar Pesanan</h2>
            <div class="orders-grid">
                @forelse($orders as $order)
                    <div class="order-item-card clickable-card" data-order-id="{{ $order->id }}"
                        data-current-status="{{ $order->status }}">
                        <div class="order-item-header">
                            <div class="order-header-top">
                                <span class="order-invoice-number">{{ $order->invoice ?? 'N/A' }}</span>
                                <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="order-customer-info">
                                <span class="order-customer-name">
                                    {{-- Perbaikan: menggunakan relasi user yang benar --}}
                                    {{ $order->user->name ?? $order->user->username ?? 'N/A' }}
                                </span>
                                <span class="order-customer-address">
                                    {{-- Perbaikan: menggunakan relasi address yang benar --}}
                                    {{ $order->address->address ?? 'Alamat tidak tersedia' }}
                                </span>
                                @if ($order->user && $order->user->phone)
                                    <span class="order-customer-phone">
                                        📞 {{ $order->user->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="order-item-content">
                            <div class="order-menu-photos">
                                @if ($order->menus && $order->menus->count() > 0)
                                    @foreach ($order->menus->take(3) as $index => $menu)
                                        <div class="menu-photo-item"
                                            style="z-index: {{ 10 - $index }};
                                 transform: translateX({{ $index * 18 }}px) translateY({{ $index * 12 }}px);">
                                            {{-- Perbaikan: menggunakan image_url seperti pada order.blade.php --}}
                                            <img src="{{ $menu->image_url }}"
                                                alt="{{ $menu->name }}" class="menu-photo-image"
                                                onerror="this.src='{{ asset('storage/menu/default-image.jpg') }}'">
                                        </div>
                                    @endforeach
                                    @if ($order->menus->count() > 3)
                                        <div class="menu-photo-count">+{{ $order->menus->count() - 3 }}</div>
                                    @endif
                                @else
                                    <div class="menu-photo-item">
                                        <img src="{{ asset('storage/menu/default-image.jpg') }}" alt="No menu"
                                            class="menu-photo-image">
                                    </div>
                                @endif
                            </div>

                            <div class="order-menu-list">
                                @if ($order->menus && $order->menus->count() > 0)
                                    @foreach ($order->menus as $menu)
                                        <div class="order-menu-item">
                                            <span
                                                class="order-menu-name">{{ $menu->name ?? 'Menu tidak tersedia' }}</span>
                                            <span class="order-menu-quantity">x{{ $menu->pivot->quantity ?? 0 }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="order-menu-item">
                                        <span class="order-menu-name">Tidak ada menu</span>
                                        <span class="order-menu-quantity">x0</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="order-item-footer">
                            <div class="order-total">
                                Total: <span class="order-total-amount">Rp
                                    {{-- Perbaikan: menggunakan perhitungan yang benar --}}
                                    {{ number_format(($order->subtotal ?? 0) + ($order->shipping_fee ?? 0) + ($order->admin_fee ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <div class="order-payment-method">{{ ucfirst($order->payment_method ?? 'N/A') }}</div>
                        </div>

                        <div class="order-status-container">
                            <button class="order-status-button status-{{ $order->status }}"
                                onclick="toggleOrderStatusDropdown(this); event.stopPropagation();"
                                data-current-status="{{ $order->status }}" data-order-id="{{ $order->id }}">
                                {{ ucfirst($order->status) }}
                            </button>
                            <div class="order-status-dropdown" style="display: none;"
                                onclick="event.stopPropagation();">
                                <button class="order-status-option {{ $order->status == 'pending' ? 'active' : '' }}"
                                    data-status="pending"
                                    onclick="selectOrderStatus(this, 'Pending'); event.stopPropagation();">Menunggu</button>
                                <button
                                    class="order-status-option {{ $order->status == 'processing' ? 'active' : '' }}"
                                    data-status="processing"
                                    onclick="selectOrderStatus(this, 'Processing'); event.stopPropagation();">Diproses</button>
                                <button class="order-status-option {{ $order->status == 'shipped' ? 'active' : '' }}"
                                    data-status="shipped"
                                    onclick="selectOrderStatus(this, 'Shipped'); event.stopPropagation();">Dikirim</button>
                                <button class="order-status-option {{ $order->status == 'delivered' ? 'active' : '' }}"
                                    data-status="delivered"
                                    onclick="selectOrderStatus(this, 'Delivered'); event.stopPropagation();">Selesai</button>
                                <button class="order-status-option {{ $order->status == 'cancelled' ? 'active' : '' }}"
                                    data-status="cancelled"
                                    onclick="selectOrderStatus(this, 'Cancelled'); event.stopPropagation();">Dibatalkan</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>Belum Ada Pesanan</h3>
                        <p>Pesanan akan muncul di sini ketika ada customer yang memesan.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Mengupdate status...</p>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>

    <!-- Pass data to JavaScript -->
    <script>
        window.statusCounts = {
            pending: {{ $statusCounts['pending'] }},
            processing: {{ $statusCounts['processing'] }},
            shipped: {{ $statusCounts['shipped'] }},
            delivered: {{ $statusCounts['delivered'] }},
            cancelled: {{ $statusCounts['cancelled'] }}
        };
    </script>

    <!-- Additional CSS -->
    <style>
        .order-customer-phone {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            margin-top: 4px;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 20px;
            border-radius: 18px;
            border: 2px solid;
            font-weight: 500;
        }

        .alert-error {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .order-item-card.clickable-card {
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .order-item-card.clickable-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .order-status-container {
            cursor: default;
        }
    </style>

    <!-- Load external JavaScript files -->
    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/admin-orders.js') }}" defer></script>
</body>

</html>
