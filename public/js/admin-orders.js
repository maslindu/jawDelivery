// public/js/admin-orders.js

class AdminOrdersManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.originalStatusCounts = {};
        this.init();
    }

    init() {
        // Get status counts from page data
        if (window.statusCounts) {
            this.originalStatusCounts = window.statusCounts;
        }

        // Bind events
        this.bindEvents();
    }

    bindEvents() {
        // Event listener untuk klik card pesanan
        this.bindCardClickEvents();

        // Event listener untuk dropdown status
        this.bindDropdownEvents();

        // Event listener untuk klik di luar dropdown
        this.bindOutsideClickEvents();
    }

    bindCardClickEvents() {
        document.querySelectorAll('.order-item-card').forEach(card => {
            card.addEventListener('click', (event) => {
                // Jangan redirect jika yang diklik adalah dropdown status
                if (event.target.closest('.order-status-container')) {
                    return;
                }
                
                const orderId = card.getAttribute('data-order-id');
                if (orderId) {
                    window.location.href = `/admin/orders-detail/${orderId}`;
                }
            });
        });
    }

    bindDropdownEvents() {
        // Prevent card click when interacting with status dropdown
        document.querySelectorAll('.order-status-container').forEach(container => {
            container.addEventListener('click', (event) => {
                event.stopPropagation();
            });
        });
    }

    bindOutsideClickEvents() {
        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.order-status-container')) {
                document.querySelectorAll('.order-status-dropdown').forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }
        });
    }

    // Toggle dropdown status
    toggleOrderStatusDropdown(button) {
        // Tutup semua dropdown lain
        document.querySelectorAll('.order-status-dropdown').forEach(dropdown => {
            if (dropdown !== button.nextElementSibling) {
                dropdown.style.display = 'none';
            }
        });

        const dropdown = button.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Select status dari dropdown
    selectOrderStatus(optionButton, statusText) {
        const dropdown = optionButton.closest('.order-status-dropdown');
        const statusButton = dropdown.previousElementSibling;
        const orderId = statusButton.getAttribute('data-order-id');
        const newStatus = optionButton.getAttribute('data-status');
        const currentStatus = statusButton.getAttribute('data-current-status');

        // Jika status sama, tutup dropdown saja
        if (newStatus === currentStatus) {
            dropdown.style.display = 'none';
            return;
        }

        // Tampilkan loading
        this.showLoading();

        // Update status via AJAX
        this.updateOrderStatus(orderId, newStatus, statusButton, dropdown);
    }

    // Function untuk update status order
    async updateOrderStatus(orderId, newStatus, statusButton, dropdown) {
        try {
            const response = await fetch(`/admin/orders/${orderId}/status`, {
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
            this.hideLoading();

            if (data.success) {
                // Update button appearance
                this.updateStatusButton(statusButton, newStatus);

                // Update dropdown active state
                this.updateDropdownActiveState(dropdown, newStatus);

                // Update status counts
                if (data.statusCounts) {
                    this.updateStatusCounts(data.statusCounts);
                }

                // Update card data attribute
                const card = statusButton.closest('.order-item-card');
                if (card) {
                    card.setAttribute('data-current-status', newStatus);
                }

                // Tutup dropdown
                dropdown.style.display = 'none';

                // Tampilkan notifikasi sukses
                this.showNotification('Status pesanan berhasil diperbarui!', 'success');

            } else {
                this.showNotification(data.message || 'Gagal memperbarui status', 'error');
            }
        } catch (error) {
            this.hideLoading();
            console.error('Error:', error);
            this.showNotification('Terjadi kesalahan saat memperbarui status', 'error');
        }
    }

    // Update tampilan button status
    updateStatusButton(button, newStatus) {
        // Remove all status classes
        button.classList.remove('status-pending', 'status-processing', 'status-shipped', 'status-delivered', 'status-cancelled');

        // Add new status class
        button.classList.add(`status-${newStatus}`);

        // Update button text
        const statusLabels = {
            'pending': 'Menunggu',
            'processing': 'Diproses',
            'shipped': 'Dikirim',
            'delivered': 'Selesai',
            'cancelled': 'Dibatalkan'
        };

        button.textContent = statusLabels[newStatus] || newStatus;
        button.setAttribute('data-current-status', newStatus);
    }

    // Update active state di dropdown
    updateDropdownActiveState(dropdown, newStatus) {
        dropdown.querySelectorAll('.order-status-option').forEach(option => {
            option.classList.remove('active');
            if (option.getAttribute('data-status') === newStatus) {
                option.classList.add('active');
            }
        });
    }

    // Update status counts di header
    updateStatusCounts(newCounts) {
        Object.keys(newCounts).forEach(status => {
            const countElement = document.getElementById(`count-${status}`);
            if (countElement) {
                countElement.textContent = newCounts[status];

                // Add animation effect
                countElement.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    countElement.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }

    // Show loading overlay
    showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }

    // Hide loading overlay
    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    // Show notification
    showNotification(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="margin-left: 10px; background: none; border: none; color: inherit; cursor: pointer;">&times;</button>
        `;

        container.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
}

// Global functions for backward compatibility
function toggleOrderStatusDropdown(button) {
    if (window.adminOrdersManager) {
        window.adminOrdersManager.toggleOrderStatusDropdown(button);
    }
}

function selectOrderStatus(optionButton, statusText) {
    if (window.adminOrdersManager) {
        window.adminOrdersManager.selectOrderStatus(optionButton, statusText);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.adminOrdersManager = new AdminOrdersManager();
});
