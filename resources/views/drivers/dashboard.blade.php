<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - JawDelivery</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        /* Driver Dashboard Styles */
        .main-content {
            min-height: calc(100vh - 100px);
            background-color: #f8f9fa;
            padding: 20px;
        }

        .driver-dashboard {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .driver-welcome {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .driver-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .driver-info p {
            color: #666;
            font-size: 16px;
        }

        .driver-status {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .driver-welcome {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .driver-status {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.header')
    
    <main class="main-content">
        <div class="driver-dashboard">
            <div class="dashboard-header">
                <div class="driver-welcome">
                    <div class="driver-info">
                        <h1>Selamat Datang, {{ Auth::user()->fullName ?? Auth::user()->username }}!</h1>
                        <p>Dashboard Driver - JawDelivery</p>
                    </div>
                    <div class="driver-status">
                        <span class="status-badge {{ $stats['status'] === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $stats['status'] === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['total_deliveries'] }}</div>
                        <div class="stat-label">Total Pengantaran</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['rating'], 1) }}</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            {{ $stats['is_available'] ? 'Tersedia' : 'Tidak Tersedia' }}
                        </div>
                        <div class="stat-label">Status Ketersediaan</div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-content">
                <p>Konten dashboard akan ditambahkan di sini...</p>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}"></script>
</body>
</html>
