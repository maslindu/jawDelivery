<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JawDelivery - Profile</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/popup-history.css') }}">
</head>

<body>
    @include('components.header')

    <div class="history-container">
        <h2 class="history-title">Riwayat Transaksi</h2>

        @php
            $statusColors = [
                'pending' => '#FFE082',
                'processing' => '#FFD54F',
                'shipped' => '#81D4FA',
                'delivered' => '#C8E6C9',
                'completed' => '#C8E6C9',
                'cancelled' => '#EF9A9A',
                'failed' => '#E57373',
            ];

            $statusLabels = [
                'pending' => 'Menunggu',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'delivered' => 'Selesai',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'failed' => 'Gagal',
            ];

            $statusIcons = [
                'pending' => '⏳',
                'processing' => '🔄',
                'shipped' => '🚚',
                'delivered' => '✅',
                'completed' => '✅',
                'cancelled' => '❌',
                'failed' => '❌',
            ];
        @endphp

        @endphp

        @foreach ($histories as $history)
            <div class="history-card" data-order-id="{{ $history['id'] }}">
                <div class="history-header">
                    <div>
                        <div class="history-order-number">{{ $history['invoice'] }}</div>
                        <div class="history-date">{{ date('d M Y', strtotime($history['date'])) }}</div>
                        <div class="history-info">Jumlah Item: <b>{{ $history['items'] }}</b></div>
                        <div class="history-info" style="margin-bottom:0;">Metode: <b>{{ $history['payment'] }}</b>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                        <div class="history-status status-badge" data-status="{{ $history['status'] }}"
                            style="background-color: {{ $statusColors[$history['status']] ?? '#ccc' }};
                           display: inline-flex;
                           align-items: center;
                           gap: 4px;
                           padding: 4px 10px;
                           border-radius: 12px;
                           font-size: 13px;
                           font-weight: 600;
                           transition: all 0.3s ease;">
                            <span class="status-icon">{{ $statusIcons[$history['status']] ?? '❓' }}</span>
                            <span
                                class="status-text">{{ $statusLabels[$history['status']] ?? $history['status'] }}</span>
                        </div>

                        <button class="detail-btn" data-id='{{ $history['id'] }}'>Detail</button>
                    </div>
                </div>

                <div class="history-total-row" style="justify-content: flex-end;">
                    <div class="history-total">
                        Total: Rp{{ number_format($history['total'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Status configuration yang konsisten dengan order.js
        const statusConfig = {
            colors: {
                'pending': '#FFE082',
                'processing': '#FFD54F',
                'shipped': '#81D4FA',
                'delivered': '#C8E6C9',
                'completed': '#C8E6C9',
                'cancelled': '#EF9A9A',
                'failed': '#E57373'
            },
            labels: {
                'pending': 'Menunggu',
                'processing': 'Diproses',
                'shipped': 'Dikirim',
                'delivered': 'Selesai',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan',
                'failed': 'Gagal'
            },
            icons: {
                'pending': '⏳',
                'processing': '🔄',
                'shipped': '🚚',
                'delivered': '✅',
                'completed': '✅',
                'cancelled': '❌',
                'failed': '❌'
            }
        };

        // Function to update status display with smooth animation
        function updateStatusDisplay(orderCard, newStatus) {
            const statusBadge = orderCard.querySelector('.status-badge');
            const statusIcon = statusBadge.querySelector('.status-icon');
            const statusText = statusBadge.querySelector('.status-text');

            // Add loading state
            statusBadge.style.opacity = '0.6';
            statusBadge.style.transform = 'scale(0.95)';

            setTimeout(() => {
                // Update badge background color
                statusBadge.style.backgroundColor = statusConfig.colors[newStatus] || '#ccc';
                statusBadge.setAttribute('data-status', newStatus);

                // Update icon and text
                statusIcon.textContent = statusConfig.icons[newStatus] || '❓';
                statusText.textContent = statusConfig.labels[newStatus] || newStatus;

                // Restore and add success animation
                statusBadge.style.opacity = '1';
                statusBadge.style.transform = 'scale(1.05)';

                setTimeout(() => {
                    statusBadge.style.transform = 'scale(1)';
                }, 200);
            }, 150);
        }

        // Function to show notification
        function showStatusUpdateNotification(orderId, newStatus) {
            const statusLabel = statusConfig.labels[newStatus] || newStatus;

            const notification = document.createElement('div');
            notification.className = 'status-notification';
            notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${statusConfig.icons[newStatus] || '📱'}</span>
                <span class="notification-text">Status pesanan diperbarui: ${statusLabel}</span>
            </div>
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Function to check status updates
        function checkStatusUpdates() {
            const orderCards = document.querySelectorAll('.history-card[data-order-id]');

            orderCards.forEach(card => {
                const orderId = card.getAttribute('data-order-id');
                const currentStatusBadge = card.querySelector('.status-badge');
                const currentStatus = currentStatusBadge.getAttribute('data-status');

                // Skip if order is in final state
                if (['delivered', 'completed', 'cancelled', 'failed'].includes(currentStatus)) {
                    return;
                }

                fetch(`/orders/${orderId}/status`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.status !== currentStatus) {
                            updateStatusDisplay(card, data.status);
                            showStatusUpdateNotification(orderId, data.status);
                        }
                    })
                    .catch(error => {
                        console.error('Error checking status for order', orderId, ':', error);
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Detail button functionality
            document.querySelectorAll('.detail-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const orderId = button.getAttribute('data-id');
                    if (orderId) {
                        window.location.href = `/order/${orderId}`;
                    }
                });
            });

            // Start periodic status checking
            const statusCheckInterval = setInterval(checkStatusUpdates, 30000);

            // Initial check
            setTimeout(checkStatusUpdates, 2000);

            // Cleanup
            window.addEventListener('beforeunload', () => {
                clearInterval(statusCheckInterval);
            });
        });

        // Check status when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(checkStatusUpdates, 1000);
            }
        });
    </script>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>
