// public/js/orders-detail.js

class OrderDetailManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.orderId = null;
        this.currentStatus = null;
        this.init();
    }

    init() {
        // Get order data from page
        const orderData = window.orderData;
        if (orderData) {
            this.orderId = orderData.id;
            this.currentStatus = orderData.status;
        }

        // Bind events
        this.bindEvents();
    }

    bindEvents() {
        // Bind status update buttons
        const statusButtons = document.querySelectorAll('.status-btn');
        statusButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const status = e.currentTarget.getAttribute('data-status');
                this.updateOrderStatus(status);
            });
        });

        // Bind back button if exists
        const backButton = document.querySelector('.back-button');
        if (backButton) {
            backButton.addEventListener('click', () => {
                window.history.back();
            });
        }
    }

    async updateOrderStatus(newStatus) {
        if (newStatus === this.currentStatus) {
            return;
        }

        // Show loading state
        this.setLoadingState(true);

        try {
            const response = await fetch(`/admin/orders/${this.orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update current status
                this.currentStatus = newStatus;
                
                // Update UI elements
                this.updateButtonStates(newStatus);
                this.updateStatusDisplay(newStatus);
                this.updateProgressSteps(newStatus);
                
                // Show success notification
                this.showNotification('Status pesanan berhasil diperbarui!', 'success');
            } else {
                this.showNotification(data.message || 'Gagal memperbarui status', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Terjadi kesalahan saat memperbarui status', 'error');
        } finally {
            this.setLoadingState(false);
        }
    }

    setLoadingState(isLoading) {
        const buttons = document.querySelectorAll('.status-btn');
        buttons.forEach(btn => {
            btn.disabled = isLoading;
            btn.style.opacity = isLoading ? '0.6' : '1';
        });
    }

    updateButtonStates(activeStatus) {
        const buttons = document.querySelectorAll('.status-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-status') === activeStatus) {
                btn.classList.add('active');
            }
        });
    }

    updateStatusDisplay(status) {
        const statusIcon = document.querySelector('.status-icon-large');
        const statusTitle = document.querySelector('.status-title');
        const statusTime = document.querySelector('.status-time');

        const statusConfig = {
            'pending': { icon: '⏳', title: 'Menunggu Konfirmasi' },
            'processing': { icon: '🔄', title: 'Sedang Diproses' },
            'shipped': { icon: '🚚', title: 'Sedang Dikirim' },
            'delivered': { icon: '✅', title: 'Pesanan Selesai' },
            'cancelled': { icon: '❌', title: 'Pesanan Dibatalkan' }
        };

        if (statusConfig[status] && statusIcon && statusTitle && statusTime) {
            statusIcon.textContent = statusConfig[status].icon;
            statusTitle.textContent = statusConfig[status].title;
            statusTime.textContent = 'Diperbarui: ' + new Date().toLocaleString('id-ID');
        }
    }

    updateProgressSteps(status) {
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

    showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
    }
}

// Utility functions for backward compatibility
function updateOrderStatus(status) {
    if (window.orderDetailManager) {
        window.orderDetailManager.updateOrderStatus(status);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.orderDetailManager = new OrderDetailManager();
});
