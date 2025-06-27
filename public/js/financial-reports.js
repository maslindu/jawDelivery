// financial-reports.js - Real data integration

class FinancialReportsManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.bindEvents();
        this.initDateValidation();
        this.autoHideAlerts();
        this.loadChartData();
    }

    bindEvents() {
        // Form submission
        const reportForm = document.getElementById('reportForm');
        if (reportForm) {
            reportForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Export buttons
        document.querySelectorAll('.btn-export').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleExport(e));
        });

        // Auto-refresh on filter change
        const filters = ['start_date', 'end_date', 'status', 'payment_method'];
        filters.forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', () => this.autoRefresh());
            }
        });

        // Search with debounce
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => this.autoRefresh(), 500);
            });
        }
    }

    initDateValidation() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (!startDate || !endDate) return;

        const validateDates = () => {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                
                if (start > end) {
                    this.showNotification('Tanggal mulai tidak boleh lebih besar dari tanggal akhir', 'error');
                    startDate.focus();
                    return false;
                }

                // Check if date range is too large (more than 1 year)
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 365) {
                    this.showNotification('Rentang tanggal maksimal 1 tahun', 'error');
                    return false;
                }
            }
            return true;
        };

        startDate.addEventListener('change', validateDates);
        endDate.addEventListener('change', validateDates);
    }

    handleFormSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }

        this.showLoading(true);
        e.target.submit();
    }

    validateForm() {
        const startDate = document.getElementById('start_date')?.value;
        const endDate = document.getElementById('end_date')?.value;

        if (!startDate || !endDate) {
            this.showNotification('Silakan pilih rentang tanggal', 'error');
            return false;
        }

        return true;
    }

    autoRefresh() {
        if (!this.validateForm()) {
            return;
        }

        const form = document.querySelector('.filter-form');
        if (form) {
            this.showLoading(true);
            form.submit();
        }
    }

    handleExport(e) {
        e.preventDefault();
        
        const button = e.currentTarget;
        const format = button.textContent.includes('Excel') ? 'excel' : 'pdf';
        
        this.exportReport(format);
    }

    exportReport(format) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '/admin/reports/export';
        
        // Get all current filter parameters
        const params = {
            format: format,
            start_date: document.getElementById('start_date')?.value || '',
            end_date: document.getElementById('end_date')?.value || '',
            status: document.getElementById('status')?.value || '',
            payment_method: document.getElementById('payment_method')?.value || '',
            search: document.getElementById('search')?.value || ''
        };
        
        // Add parameters to form
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
        
        this.showNotification(`Export ${format.toUpperCase()} sedang diproses...`, 'info');
    }

    async loadChartData() {
        try {
            const startDate = document.getElementById('start_date')?.value;
            const endDate = document.getElementById('end_date')?.value;
            
            if (!startDate || !endDate) return;

            const response = await fetch(`/admin/reports/revenue-data?start_date=${startDate}&end_date=${endDate}&period=daily`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderChart(data.data);
            }
        } catch (error) {
            console.error('Error loading chart data:', error);
        }
    }

    renderChart(data) {
        // Simple chart implementation using Canvas or Chart.js if available
        const chartContainer = document.getElementById('revenueChart');
        if (!chartContainer || !data || data.length === 0) return;

        // Create simple visual representation
        const maxRevenue = Math.max(...data.map(d => d.revenue));
        const chartHTML = data.map(item => {
            const height = (item.revenue / maxRevenue) * 100;
            const date = new Date(item.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            
            return `
                <div class="chart-bar" style="height: ${height}%; position: relative;">
                    <div class="bar-value" style="position: absolute; top: -20px; font-size: 10px;">
                        Rp ${this.formatCurrency(item.revenue)}
                    </div>
                    <div class="bar-date" style="position: absolute; bottom: -20px; font-size: 10px;">
                        ${date}
                    </div>
                </div>
            `;
        }).join('');

        chartContainer.innerHTML = `
            <div class="simple-chart" style="display: flex; align-items: end; height: 200px; gap: 2px;">
                ${chartHTML}
            </div>
        `;
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    showLoading(show) {
        const existingLoader = document.getElementById('reportLoader');
        
        if (show) {
            if (!existingLoader) {
                const loader = document.createElement('div');
                loader.id = 'reportLoader';
                loader.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.5);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                    ">
                        <div style="
                            background: white;
                            padding: 30px;
                            border-radius: 10px;
                            text-align: center;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        ">
                            <div class="spinner" style="
                                width: 40px;
                                height: 40px;
                                border: 4px solid #f3f3f3;
                                border-top: 4px solid #fe4a49;
                                border-radius: 50%;
                                animation: spin 1s linear infinite;
                                margin: 0 auto 15px;
                            "></div>
                            <p>Memuat laporan...</p>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(loader);
            }
        } else {
            if (existingLoader) {
                existingLoader.remove();
            }
        }
    }

    showNotification(message, type) {
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
            max-width: 350px;
        `;
        
        // Set background color based on type
        const colors = {
            success: '#28a745',
            info: '#17a2b8',
            error: '#fe4a49',
            warning: '#ffc107'
        };
        
        notification.style.backgroundColor = colors[type] || colors.info;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after delay
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    autoHideAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }, 5000);
        });
    }

    // Real-time updates (could be enhanced with WebSockets)
    startRealTimeUpdates() {
        // Poll for updates every 30 seconds
        setInterval(() => {
            this.checkForUpdates();
        }, 30000);
    }

    async checkForUpdates() {
        try {
            const response = await fetch('/admin/reports/latest-stats', {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateStatsCards(data);
            }
        } catch (error) {
            console.error('Error checking for updates:', error);
        }
    }

    updateStatsCards(data) {
        // Update summary cards with new data
        const summaryCards = document.querySelectorAll('.summary-amount');
        summaryCards.forEach((card, index) => {
            if (data[index]) {
                card.textContent = data[index];
                // Add subtle animation
                card.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.reportsManager = new FinancialReportsManager();
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .chart-bar {
            background: linear-gradient(to top, #fe4a49, #ff6b6b);
            min-height: 10px;
            border-radius: 2px 2px 0 0;
            margin: 0 1px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .chart-bar:hover {
            transform: scale(1.05);
            opacity: 0.8;
        }
        
        .simple-chart {
            padding: 20px 0;
        }
    `;
    document.head.appendChild(style);
});

// Global functions for backward compatibility
function generateReport() {
    if (window.reportsManager) {
        const form = document.querySelector('.filter-form');
        if (form) {
            window.reportsManager.showLoading(true);
            form.submit();
        }
    }
}

function resetFilter() {
    const form = document.querySelector('.filter-form');
    if (form) {
        // Reset all form fields
        form.reset();
        
        // Set default dates (last 30 days)
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);
        
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (startDateInput) startDateInput.value = startDate.toISOString().split('T')[0];
        if (endDateInput) endDateInput.value = endDate.toISOString().split('T')[0];
        
        // Submit form
        window.reportsManager.showLoading(true);
        form.submit();
    }
}

function exportReport(format) {
    if (window.reportsManager) {
        window.reportsManager.exportReport(format);
    }
}