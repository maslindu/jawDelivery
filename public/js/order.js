// Order state configurations yang konsisten dengan backend
const orderStates = {
    pending: {
        title: "Pesanan Diterima",
        subtitle: "Pesanan Anda sedang menunggu konfirmasi dari restoran.",
        icon: `
            <div class="clock-face">
                <div class="clock-hand hour-hand"></div>
                <div class="clock-hand minute-hand"></div>
                <div class="clock-center"></div>
            </div>
        `,
        statusText: "Menunggu Konfirmasi",
        statusDot: "pending",
        footerNote: "Pesanan akan dikonfirmasi dalam 1x24 jam. Jika ada kendala, hubungi customer service kami."
    },
    processing: {
        title: "Pesanan Sedang Diproses",
        subtitle: "Chef kami sedang menyiapkan pesanan Anda dengan penuh perhatian.",
        icon: `<div class="clock-face cooking-icon">👨‍🍳</div>`,
        statusText: "Sedang Diproses",
        statusDot: "cooking",
        footerNote: "Estimasi waktu memasak: 15-30 menit. Kami akan segera mengirimkan pesanan Anda."
    },
    shipped: {
        title: "Pesanan Sedang Dikirim",
        subtitle: "Kurir kami sedang dalam perjalanan menuju lokasi Anda.",
        icon: `<div class="clock-face delivery-icon">🚚</div>`,
        statusText: "Sedang Dikirim",
        statusDot: "delivery",
        footerNote: "Estimasi waktu tiba: 10-20 menit. Pastikan Anda berada di lokasi pengiriman."
    },
    delivered: {
        title: "Pesanan Selesai",
        subtitle: "Terima kasih! Pesanan Anda telah berhasil diterima. Selamat menikmati!",
        icon: `<div class="clock-face completed-icon">✅</div>`,
        statusText: "Pesanan Selesai",
        statusDot: "completed",
        footerNote: "Jangan lupa berikan rating dan review untuk membantu kami memberikan layanan yang lebih baik."
    },
    cancelled: {
        title: "Pesanan Dibatalkan",
        subtitle: "Pesanan Anda telah dibatalkan. Dana akan dikembalikan dalam 1-3 hari kerja.",
        icon: `<div class="clock-face cancelled-icon">❌</div>`,
        statusText: "Pesanan Dibatalkan",
        statusDot: "cancelled",
        footerNote: "Jika Anda memiliki pertanyaan tentang pembatalan ini, silakan hubungi customer service kami."
    }
};

// Global variables
let currentStatus = null;
let orderId = null;
let statusCheckInterval = null;

// Function to change order state with smooth animation
function changeOrderState(state) {
    const config = orderStates[state];
    if (!config) {
        console.warn('Unknown order state:', state);
        return;
    }

    // Add loading animation
    const statusSection = document.querySelector('.status-section');
    statusSection.style.opacity = '0.7';
    statusSection.style.transform = 'scale(0.98)';

    setTimeout(() => {
        // Update all elements
        document.getElementById('statusTitle').textContent = config.title;
        document.getElementById('statusSubtitle').textContent = config.subtitle;
        document.getElementById('statusIcon').innerHTML = config.icon;
        document.getElementById('statusText').textContent = config.statusText;
        document.getElementById('footerNote').textContent = config.footerNote;

        const statusDot = document.getElementById('statusDot');
        statusDot.className = `status-dot ${config.statusDot}`;

        // Restore with success animation
        statusSection.style.opacity = '1';
        statusSection.style.transform = 'scale(1.02)';

        setTimeout(() => {
            statusSection.style.transform = 'scale(1)';
        }, 300);
    }, 200);

    // Update current status
    currentStatus = state;
    if (window.orderData) {
        window.orderData.currentStatus = state;
    }
}

// Function to check status updates from server
function checkStatusUpdate() {
    if (!orderId || !currentStatus) return;

    // Skip checking if order is in final state
    if (['delivered', 'cancelled'].includes(currentStatus)) {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
            statusCheckInterval = null;
        }
        return;
    }

    fetch(`/orders/${orderId}/status`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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
            console.log('Status updated from', currentStatus, 'to', data.status);
            changeOrderState(data.status);
            
            // Show notification
            showStatusUpdateNotification(data.status);
        }
    })
    .catch(error => {
        console.error('Error checking status:', error);
    });
}

// Function to show status update notification
function showStatusUpdateNotification(newStatus) {
    const config = orderStates[newStatus];
    if (!config) return;

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'status-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">🔔</span>
            <span class="notification-text">Status diperbarui: ${config.statusText}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Get order data from window object
    if (window.orderData) {
        orderId = window.orderData.orderId;
        currentStatus = window.orderData.currentStatus;
        
        // Set initial state
        changeOrderState(currentStatus);
        
        // Start periodic status checking (every 30 seconds)
        statusCheckInterval = setInterval(checkStatusUpdate, 30000);
        
        // Initial check after 2 seconds
        setTimeout(checkStatusUpdate, 2000);
    } else {
        console.error('Order data not found');
    }
});

// Check status when page becomes visible again
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && orderId && currentStatus) {
        setTimeout(checkStatusUpdate, 1000);
    }
});

// Cleanup interval when page unloads
window.addEventListener('beforeunload', function() {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }
});
