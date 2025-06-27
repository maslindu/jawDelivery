<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Detail Driver</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manage-driver.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <div class="manage-driver-container">
            <div class="driver-section">
                <div class="section-header">
                    <h2 class="section-title">Detail Driver</h2>
                    <div class="header-actions">
                        <a href="{{ route('drivers.edit', $driver) }}" class="btn-edit">Edit Driver</a>
                        <a href="{{ route('drivers.index') }}" class="btn-back">← Kembali</a>
                    </div>
                </div>

                <div class="driver-detail">
                    <div class="driver-profile">
                        <div class="profile-avatar">
                            @if($driver->user->avatar_link)
                                <img src="{{ $driver->user->avatar_url }}" alt="Avatar {{ $driver->user->fullName }}" class="avatar-image-large">
                            @else
                                <div class="avatar-placeholder-large"></div>
                            @endif
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">{{ $driver->user->fullName ?? $driver->user->username }}</h3>
                            <div class="profile-status">
                                <span class="status-badge status-{{ $driver->status }}">
                                    {{ ucfirst($driver->status) }}
                                </span>
                                @if($driver->is_available)
                                    <span class="availability-badge available">Tersedia</span>
                                @else
                                    <span class="availability-badge unavailable">Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-sections">
                        <div class="detail-section">
                            <h4 class="detail-section-title">Informasi Kontak</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Username:</span>
                                    <span class="detail-value">{{ $driver->user->username }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value">{{ $driver->user->email }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Telepon:</span>
                                    <span class="detail-value">{{ $driver->user->phone }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Alamat:</span>
                                    <span class="detail-value">{{ $driver->user->address ?? 'Tidak ada' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h4 class="detail-section-title">Informasi Kendaraan</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Jenis Kendaraan:</span>
                                    <span class="detail-value">{{ $driver->vehicle_type }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Plat Nomor:</span>
                                    <span class="detail-value">{{ $driver->license_plate }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Nomor SIM:</span>
                                    <span class="detail-value">{{ $driver->driver_license ?? 'Tidak ada' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Nomor STNK:</span>
                                    <span class="detail-value">{{ $driver->vehicle_registration ?? 'Tidak ada' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <h4 class="detail-section-title">Statistik Driver</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Rating:</span>
                                    <span class="detail-value">
                                        {{ $driver->rating }}/5.00
                                        <span class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($driver->rating))
                                                    ⭐
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Total Pengiriman:</span>
                                    <span class="detail-value">{{ $driver->total_deliveries }} pengiriman</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Bergabung:</span>
                                    <span class="detail-value">{{ $driver->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Terakhir Update:</span>
                                    <span class="detail-value">{{ $driver->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button onclick="toggleAvailability({{ $driver->id }})" class="btn-toggle-availability">
                            {{ $driver->is_available ? 'Set Tidak Tersedia' : 'Set Tersedia' }}
                        </button>
                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-driver" onclick="return confirm('Yakin ingin menghapus driver ini? Tindakan ini tidak dapat dibatalkan.')">
                                Hapus Driver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script>
        async function toggleAvailability(driverId) {
            try {
                const response = await fetch(`/drivers/${driverId}/toggle-availability`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengubah status: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }
    </script>
</body>
</html>
