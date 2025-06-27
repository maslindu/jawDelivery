<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="section-header">
                    <h2 class="section-title">Kelola Driver</h2>
                    <a href="{{ route('drivers.create') }}" class="btn-add-driver">
                        <span>+</span> Tambah Driver
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="driver-list">
                    @forelse($drivers as $driver)
                        <div class="driver-card">
                            <div class="driver-avatar">
                                @if($driver->user->avatar_link)
                                    <img src="{{ $driver->user->avatar_url }}" alt="Avatar {{ $driver->user->fullName }}" class="avatar-image">
                                @else
                                    <div class="avatar-placeholder"></div>
                                @endif
                            </div>
                            <div class="driver-info">
                                <h3 class="driver-name">{{ $driver->user->fullName ?? $driver->user->username }}</h3>
                                <p class="driver-email">{{ $driver->user->email }}</p>
                                <p class="driver-phone">{{ $driver->user->phone }}</p>
                                <p class="driver-vehicle">
                                    {{ $driver->vehicle_type }} | {{ $driver->license_plate }}
                                </p>
                                <div class="driver-status">
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
                            <div class="driver-actions">
                                <a href="{{ route('drivers.show', $driver) }}" class="btn-action btn-view" title="Lihat Detail">
                                    👁️
                                </a>
                                <a href="{{ route('drivers.edit', $driver) }}" class="btn-action btn-edit" title="Edit">
                                    ✏️
                                </a>
                                <button onclick="toggleAvailability({{ $driver->id }})" class="btn-action btn-toggle" title="Toggle Ketersediaan">
                                    {{ $driver->is_available ? '🔴' : '🟢' }}
                                </button>
                                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="delete-form" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus driver ini?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <p>Belum ada driver yang terdaftar.</p>
                            <a href="{{ route('drivers.create') }}" class="btn-add-driver">Tambah Driver Pertama</a>
                        </div>
                    @endforelse
                </div>

                @if($drivers->hasPages())
                    <div class="pagination-wrapper">
                        {{ $drivers->links() }}
                    </div>
                @endif
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
