// financial-reports.js

// Sample delivery orders data
const deliveryOrders = [
    {
        date: '2024-06-23',
        orderId: 'ORD-001',
        customer: 'Ahmad Wijaya',
        menu: 'Nasi Gudeg + Es Teh',
        location: 'Jl. Malioboro, Yogyakarta',
        deliveryFee: 8000,
        totalOrder: 35000,
        status: 'completed'
    },
    {
        date: '2024-06-23',
        orderId: 'ORD-002',
        customer: 'Siti Nurhaliza',
        menu: 'Ayam Bakar + Nasi + Es Jeruk',
        location: 'Jl. Solo, Yogyakarta',
        deliveryFee: 10000,
        totalOrder: 45000,
        status: 'completed'
    },
    {
        date: '2024-06-22',
        orderId: 'ORD-003',
        customer: 'Budi Santoso',
        menu: 'Soto Ayam + Kerupuk',
        location: 'Jl. Kaliurang, Yogyakarta',
        deliveryFee: 12000,
        totalOrder: 28000,
        status: 'completed'
    },
    {
        date: '2024-06-22',
        orderId: 'ORD-004',
        customer: 'Maya Sari',
        menu: 'Gado-gado + Es Campur',
        location: 'Jl. Parangtritis, Yogyakarta',
        deliveryFee: 15000,
        totalOrder: 32000,
        status: 'completed'
    },
    {
        date: '2024-06-21',
        orderId: 'ORD-005',
        customer: 'Andi Pratama',
        menu: 'Nasi Pecel + Tahu Tempe',
        location: 'Jl. Wates, Yogyakarta',
        deliveryFee: 8000,
        totalOrder: 25000,
        status: 'completed'
    },
    {
        date: '2024-06-21',
        orderId: 'ORD-006',
        customer: 'Rina Melati',
        menu: 'Bakso Spesial + Es Teh Manis',
        location: 'Jl. Imogiri, Yogyakarta',
        deliveryFee: 10000,
        totalOrder: 38000,
        status: 'completed'
    },
    {
        date: '2024-06-20',
        orderId: 'ORD-007',
        customer: 'Dedi Kurniawan',
        menu: 'Mie Ayam + Pangsit + Es Jeruk',
        location: 'Jl. Bantul, Yogyakarta',
        deliveryFee: 12000,
        totalOrder: 42000,
        status: 'completed'
    },
    {
        date: '2024-06-20',
        orderId: 'ORD-008',
        customer: 'Indah Permata',
        menu: 'Nasi Rames + Sayur Asem',
        location: 'Jl. Godean, Yogyakarta',
        deliveryFee: 8000,
        totalOrder: 30000,
        status: 'completed'
    },
    {
        date: '2024-06-19',
        orderId: 'ORD-009',
        customer: 'Fajar Nugroho',
        menu: 'Rawon + Nasi + Kerupuk',
        location: 'Jl. Ringroad, Yogyakarta',
        deliveryFee: 15000,
        totalOrder: 48000,
        status: 'completed'
    },
    {
        date: '2024-06-19',
        orderId: 'ORD-010',
        customer: 'Lisa Maharani',
        menu: 'Pecel Lele + Sambal + Es Teh',
        location: 'Jl. Magelang, Yogyakarta',
        deliveryFee: 10000,
        totalOrder: 26000,
        status: 'completed'
    }
];

// Format currency to Indonesian Rupiah
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Format date to Indonesian format
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Generate report based on date filters
function generateReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Filter orders based on date range
    const filteredOrders = deliveryOrders.filter(order => {
        const orderDate = new Date(order.date);
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        return orderDate >= start && orderDate <= end;
    });
    
    // Update summary cards
    updateSummaryCards(filteredOrders, startDate, endDate);
    
    // Update table
    updateOrderTable(filteredOrders);
    
    // Show success notification
    showNotification('Laporan berhasil di-generate!', 'success');
}

// Update summary cards with calculated values
function updateSummaryCards(orders, startDate, endDate) {
    const totalRevenue = orders.reduce((sum, order) => sum + order.totalOrder, 0);
    const totalOrders = orders.length;
    const averageOrder = totalOrders > 0 ? totalRevenue / totalOrders : 0;
    
    // Update DOM elements
    document.querySelector('.summary-card:nth-child(1) .summary-amount').textContent = formatCurrency(totalRevenue);
    document.querySelector('.summary-card:nth-child(2) .summary-amount').textContent = totalOrders.toLocaleString('id-ID');
    document.querySelector('.summary-card:nth-child(3) .summary-amount').textContent = formatCurrency(averageOrder);
    
    // Update period text
    const periodText = `${formatDate(startDate)} - ${formatDate(endDate)}`;
    document.querySelector('.summary-card:nth-child(1) .summary-period').textContent = periodText;
}

// Update order table with filtered data
function updateOrderTable(orders) {
    const tbody = document.getElementById('reportTableBody');
    
    if (orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                    Tidak ada data order untuk periode yang dipilih
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = orders.map(order => {
        return `
            <tr>
                <td>${formatDate(order.date)}</td>
                <td>${order.orderId}</td>
                <td>${order.customer}</td>
                <td>${order.menu}</td>
                <td>${order.location}</td>
                <td>${formatCurrency(order.deliveryFee)}</td>
                <td class="amount">${formatCurrency(order.totalOrder)}</td>
                <td><span class="status success">Selesai</span></td>
            </tr>
        `;
    }).join('');
}

// Reset filter form
function resetFilter() {
    document.getElementById('startDate').value = '2024-06-01';
    document.getElementById('endDate').value = '2024-06-23';
    generateReport();
}

// Export report functionality
function exportReport(format) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (format === 'excel') {
        showNotification('Export Excel sedang diproses...', 'info');
        // Simulate export process
        setTimeout(() => {
            showNotification('Laporan Excel berhasil diunduh!', 'success');
        }, 2000);
    } else if (format === 'pdf') {
        showNotification('Export PDF sedang diproses...', 'info');
        // Simulate export process
        setTimeout(() => {
            showNotification('Laporan PDF berhasil diunduh!', 'success');
        }, 2000);
    }
}

// Show notification
function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Style the notification
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
    
    // Set background color based on type
    if (type === 'success') {
        notification.style.backgroundColor = '#28a745';
    } else if (type === 'info') {
        notification.style.backgroundColor = '#17a2b8';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#fe4a49';
    }
    
    // Add to document
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set up form submission
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        generateReport();
    });
    
    // Generate initial report
    generateReport();
});