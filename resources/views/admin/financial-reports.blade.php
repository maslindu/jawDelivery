<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Laporan Pendapatan</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/financial-reports.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <!-- Page Header -->
        <header class="page-header">
            <h1 class="page-title">Laporan Pendapatan</h1>
        </header>

        <!-- Filter Section -->
        <section class="filter-card">
            <div class="filter-header">
                <h2>Filter Laporan</h2>
                <div class="filter-icon">📊</div>
            </div>
            <form id="reportForm" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="startDate">Tanggal Mulai</label>
                        <input type="date" id="startDate" name="startDate" value="2024-06-01">
                    </div>
                    <div class="form-group">
                        <label for="endDate">Tanggal Akhir</label>
                        <input type="date" id="endDate" name="endDate" value="2024-06-23">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="resetFilter()">Reset</button>
                    <button type="submit" class="btn-primary">Generate Laporan</button>
                </div>
            </form>
        </section>

        <!-- Summary Cards -->
        <section class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon">💰</div>
                <div class="summary-content">
                    <h3>Total Pendapatan</h3>
                    <p class="summary-amount">Rp 2.450.000</p>
                    <span class="summary-period">1 - 23 Juni 2024</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">📦</div>
                <div class="summary-content">
                    <h3>Total Order</h3>
                    <p class="summary-amount">85</p>
                    <span class="summary-period">Order berhasil</span>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon">📈</div>
                <div class="summary-content">
                    <h3>Rata-rata Order</h3>
                    <p class="summary-amount">Rp 28.824</p>
                    <span class="summary-period">Per order</span>
                </div>
            </div>
        </section>

        <!-- Report Table -->
        <section class="report-card">
            <div class="report-header">
                <h2>Detail Order</h2>
                <div class="report-actions">
                    <button class="btn-export" onclick="exportReport('excel')">📊 Export Excel</button>
                    <button class="btn-export" onclick="exportReport('pdf')">📄 Export PDF</button>
                </div>
            </div>
            <div class="table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Menu</th>
                            <th>Lokasi Pengiriman</th>
                            <th>Biaya Delivery</th>
                            <th>Total Order</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        @php
                            $orders = [
                                ['2024-06-23','ORD-001','Ahmad Wijaya','Nasi Gudeg + Es Teh','Jl. Malioboro, Yogyakarta','Rp 8,000','Rp 35,000'],
                                ['2024-06-23','ORD-002','Siti Nurhaliza','Ayam Bakar + Nasi + Es Jeruk','Jl. Solo, Yogyakarta','Rp 10,000','Rp 45,000'],
                                ['2024-06-22','ORD-003','Budi Santoso','Soto Ayam + Kerupuk','Jl. Kaliurang, Yogyakarta','Rp 12,000','Rp 28,000'],
                                ['2024-06-22','ORD-004','Maya Sari','Gado-gado + Es Campur','Jl. Parangtritis, Yogyakarta','Rp 15,000','Rp 32,000'],
                                ['2024-06-21','ORD-005','Andi Pratama','Nasi Pecel + Tahu Tempe','Jl. Wates, Yogyakarta','Rp 8,000','Rp 25,000'],
                                ['2024-06-21','ORD-006','Rina Melati','Bakso Spesial + Es Teh Manis','Jl. Imogiri, Yogyakarta','Rp 10,000','Rp 38,000'],
                                ['2024-06-20','ORD-007','Dedi Kurniawan','Mie Ayam + Pangsit + Es Jeruk','Jl. Bantul, Yogyakarta','Rp 12,000','Rp 42,000'],
                                ['2024-06-20','ORD-008','Indah Permata','Nasi Rames + Sayur Asem','Jl. Godean, Yogyakarta','Rp 8,000','Rp 30,000'],
                                ['2024-06-19','ORD-009','Fajar Nugroho','Rawon + Nasi + Kerupuk','Jl. Ringroad, Yogyakarta','Rp 15,000','Rp 48,000'],
                                ['2024-06-19','ORD-010','Lisa Maharani','Pecel Lele + Sambal + Es Teh','Jl. Magelang, Yogyakarta','Rp 10,000','Rp 26,000']
                            ];
                        @endphp
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order[0] }}</td>
                                <td>{{ $order[1] }}</td>
                                <td>{{ $order[2] }}</td>
                                <td>{{ $order[3] }}</td>
                                <td>{{ $order[4] }}</td>
                                <td>{{ $order[5] }}</td>
                                <td class="amount">{{ $order[6] }}</td>
                                <td><span class="status success">Selesai</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <button class="pagination-btn" disabled>‹ Previous</button>
                <span class="pagination-info">Halaman 1 dari 9</span>
                <button class="pagination-btn">Next ›</button>
            </div>
        </section>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/financial-reports.js') }}" defer></script>
</body>
</html>
