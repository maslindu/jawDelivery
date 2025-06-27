<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Dashboard Admin</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        .main-content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .card-description {
            color: #6b7280;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .card-button {
            background: #3b82f6;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .card-button:hover {
            background: #2563eb;
            color: white;
            text-decoration: none;
        }

        .card-button.orders { background: #3b82f6; }
        .card-button.orders:hover { background: #2563eb; }

        .card-button.menu { background: #10b981; }
        .card-button.menu:hover { background: #059669; }

        .card-button.reports { background: #f59e0b; }
        .card-button.reports:hover { background: #d97706; }

        .card-button.drivers { background: #8b5cf6; }
        .card-button.drivers:hover { background: #7c3aed; }

        .card-button.users { background: #ef4444; }
        .card-button.users:hover { background: #dc2626; }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
            
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Halo, {{ auth()->user()->fullName ?? auth()->user()->username }}! 👋</h1>
            <p class="welcome-subtitle">Selamat datang di dashboard admin JawDelivery</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid">
            <!-- Orders Card -->
            <div class="dashboard-card">
                <span class="card-icon">📦</span>
                <h3 class="card-title">Kelola Pesanan</h3>
                <p class="card-description">Lihat dan kelola semua pesanan yang masuk dari pelanggan</p>
                <a href="{{ route('admin.orders') }}" class="card-button orders">Lihat Pesanan</a>
            </div>

            <!-- Menu Management Card -->
            <div class="dashboard-card">
                <span class="card-icon">🍽️</span>
                <h3 class="card-title">Kelola Menu</h3>
                <p class="card-description">Tambah, edit, dan hapus menu makanan yang tersedia</p>
                <a href="{{ route('admin.manage-menu') }}" class="card-button menu">Kelola Menu</a>
            </div>

            <!-- Reports Card -->
            <div class="dashboard-card">
                <span class="card-icon">📊</span>
                <h3 class="card-title">Laporan Keuangan</h3>
                <p class="card-description">Lihat laporan penjualan dan analisis keuangan</p>
                <a href="{{ route('admin.financial-reports') }}" class="card-button reports">Lihat Laporan</a>
            </div>

            <!-- Driver Management Card -->
            <div class="dashboard-card">
                <span class="card-icon">🚗</span>
                <h3 class="card-title">Kelola Driver</h3>
                <p class="card-description">Kelola data driver dan status ketersediaan mereka</p>
                <a href="{{ route('admin.manage-driver') }}" class="card-button drivers">Kelola Driver</a>
            </div>

            <!-- User Management Card -->
            <div class="dashboard-card">
                <span class="card-icon">👥</span>
                <h3 class="card-title">Kelola Pengguna</h3>
                <p class="card-description">Kelola data pengguna, admin, dan pelanggan</p>
                <a href="{{ route('admin.manage-users') }}" class="card-button users">Kelola Pengguna</a>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
