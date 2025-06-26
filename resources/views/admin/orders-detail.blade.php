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

        <div class="details-container">
            <!-- Order Section -->
            <div class="order-section">
                <h2 class="section-title">📋 Detail Pesanan</h2>
                
                <div class="order-header">
                    <h3 style="text-align: center; margin-bottom: 0.5rem;">{{ $order->invoice ?? 'N/A' }}</h3>
                    <div class="order-meta">
                        <span class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>

                <div class="order-items">
                    @if ($order->menus && $order->menus->count() > 0)
                        @foreach ($order->menus as $menu)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ $menu->image_url }}" 
                                         alt="{{ $menu->name }}" 
                                         style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;"
                                         onerror="this.src='{{ asset('storage/menu/default-image.jpg') }}'">
                                </div>
                                <div class="item-details">
                                    <div class="item-name">{{ $menu->name }}</div>
                                    <div class="item-description">{{ $menu->description ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                                <div class="item-quantity">
                                    <span class="quantity-badge">x{{ $menu->pivot->quantity }}</span>
                                </div>
                                <div class="item-price">
                                    <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    <span class="subtotal">Rp {{ number_format($menu->price * $menu->pivot->quantity, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="order-item">
                            <div class="item-details">
                                <div class="item-name">Tidak ada menu</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="order-total">
                    <div class="total-row">
                        <span class="total-label">Subtotal:</span>
                        <span>Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Ongkir:</span>
                        <span>Rp {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Biaya Admin:</span>
                        <span>Rp {{ number_format($order->admin_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row" style="border-top: 2px solid #e9ecef; padding-top: 0.5rem; margin-top: 0.5rem;">
                        <span class="total-label">Total:</span>
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

            <!-- Status Section -->
            <div class="status-section">
                <h2 class="section-title">📊 Status Pesanan</h2>
                
                <div class="status-container">
                    <div class="current-status">
                        <div class="status-icon">
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

                    <div class="status-actions">
                        <div class="actions-title">Ubah Status Pesanan</div>
                        <div class="status-buttons">
                            <button class="status-btn {{ $order->status == 'pending' ? 'active' : '' }}" 
                                    data-status="pending" onclick="updateOrderStatus('pending')">
                                <div class="btn-icon">⏳</div>
                                <div class="btn-text">Menunggu</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'processing' ? 'active' : '' }}" 
                                    data-status="processing" onclick="updateOrderStatus('processing')">
                                <div class="btn-icon">🔄</div>
                                <div class="btn-text">Diproses</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'shipped' ? 'active' : '' }}" 
                                    data-status="shipped" onclick="updateOrderStatus('shipped')">
                                <div class="btn-icon">🚚</div>
                                <div class="btn-text">Dikirim</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'delivered' ? 'active' : '' }}" 
                                    data-status="delivered" onclick="updateOrderStatus('delivered')">
                                <div class="btn-icon">✅</div>
                                <div class="btn-text">Selesai</div>
                            </button>
                            <button class="status-btn {{ $order->status == 'cancelled' ? 'active' : '' }}" 
                                    data-status="cancelled" onclick="updateOrderStatus('cancelled')">
                                <div class="btn-icon">❌</div>
                                <div class="btn-text">Dibatalkan</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="address-section">
            <h2 class="section-title">📍 Alamat Pengiriman</h2>
            <div class="address-content">
                <div class="address-icon">📍</div>
                <div class="address-details">
                    <div class="customer-name">{{ $order->user->name ?? $order->user->username ?? 'N/A' }}</div>
                    @if ($order->user && $order->user->phone)
                        <div class="customer-phone">📞 {{ $order->user->phone }}</div>
                    @endif
                    <div class="customer-address">{{ $order->address->address ?? 'Alamat tidak tersedia' }}</div>
                    <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                        <strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Notification Container -->
    <div id="notification" class="notification"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const orderId = {{ $order->id }};
        let currentStatus = '{{ $order->status }}';

        function updateOrderStatus(newStatus) {
            if (newStatus === currentStatus) {
                return;
            }

            // Show loading state
            const buttons = document.querySelectorAll('.status-btn');
            buttons.forEach(btn => btn.disabled = true);

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update current status
                    currentStatus = newStatus;
                    
                    // Update button states
                    updateButtonStates(newStatus);
                    
                    // Update status display
                    updateStatusDisplay(newStatus);
                    
                    // Update progress steps
                    updateProgressSteps(newStatus);
                    
                    // Show success notification
                    showNotification('Status pesanan berhasil diperbarui!', 'success');
                } else {
                    showNotification(data.message || 'Gagal memperbarui status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat memperbarui status', 'error');
            })
            .finally(() => {
                // Re-enable buttons
                buttons.forEach(btn => btn.disabled = false);
            });
        }

        function updateButtonStates(activeStatus) {
            const buttons = document.querySelectorAll('.status-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-status') === activeStatus) {
                    btn.classList.add('active');
                }
            });
        }

        function updateStatusDisplay(status) {
            const statusIcon = document.querySelector('.status-icon');
            const statusTitle = document.querySelector('.status-title');
            const statusTime = document.querySelector('.status-time');

            const statusConfig = {
                'pending': { icon: '⏳', title: 'Menunggu Konfirmasi' },
                'processing': { icon: '🔄', title: 'Sedang Diproses' },
                'shipped': { icon: '🚚', title: 'Sedang Dikirim' },
                'delivered': { icon: '✅', title: 'Pesanan Selesai' },
                'cancelled': { icon: '❌', title: 'Pesanan Dibatalkan' }
            };

            if (statusConfig[status]) {
                statusIcon.textContent = statusConfig[status].icon;
                statusTitle.textContent = statusConfig[status].title;
                statusTime.textContent = 'Diperbarui: ' + new Date().toLocaleString('id-ID');
            }
        }

        function updateProgressSteps(status) {
            const steps = document.querySelectorAll('.progress-step');
            const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
            const currentIndex = statusOrder.indexOf(status);

            steps.forEach((step, index) => {
                step.classList.remove('active', 'completed');
                
                if (status === 'cancelled') {
                    // If cancelled, don't show any progress
                    return;
                }
                
                if (index < currentIndex) {
                    step.classList.add('completed');
                } else if (index === currentIndex) {
                    step.classList.add('active');
                }
            });
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
    </script>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>
