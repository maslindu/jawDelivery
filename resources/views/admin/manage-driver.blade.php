<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Manage Driver</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manage-driver.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <div class="manage-driver-container">
            <div class="driver-section">
                <h2 class="section-title">Kelola Driver</h2>
                
                <div class="driver-list">
                    <?php
                    // Dummy driver data
                    $drivers = [
                        [
                            'name' => 'Nama driver',
                            'email' => 'email@email.com',
                            'phone' => '08123456788',
                            'vehicle_type' => 'Jenis Kendaraan',
                            'license_plate' => 'Plat Nomor'
                        ],
                        [
                            'name' => 'Asep Resing',
                            'email' => 'asepmberttr@email.com',
                            'phone' => '08123456788',
                            'vehicle_type' => 'Motor',
                            'license_plate' => 'AD 1234 XX'
                        ],
                        [
                            'name' => 'Masniga Ngepot',
                            'email' => 'masniga123@email.com',
                            'phone' => '08123456788',
                            'vehicle_type' => 'Mobil',
                            'license_plate' => 'AD 9090 RR'
                        ]
                    ];

                    foreach ($drivers as $driver): ?>
                        <div class="driver-card">
                            <div class="driver-avatar">
                                <div class="avatar-placeholder"></div>
                            </div>
                            <div class="driver-info">
                                <h3 class="driver-name"><?php echo htmlspecialchars($driver['name']); ?></h3>
                                <p class="driver-email"><?php echo htmlspecialchars($driver['email']); ?></p>
                                <p class="driver-phone"><?php echo htmlspecialchars($driver['phone']); ?></p>
                                <p class="driver-vehicle">
                                    <?php echo htmlspecialchars($driver['vehicle_type']); ?> | 
                                    <?php echo htmlspecialchars($driver['license_plate']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>