<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Edit Driver</title>
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
                    <h2 class="section-title">Edit Driver: {{ $driver->user->fullName ?? $driver->user->username }}</h2>
                    <a href="{{ route('drivers.index') }}" class="btn-back">← Kembali</a>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('drivers.update', $driver) }}" method="POST" class="driver-form">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h3 class="form-section-title">Informasi Pengguna</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $driver->user->username) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $driver->user->email) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullName" class="form-label">Nama Lengkap *</label>
                            <input type="text" id="fullName" name="fullName" class="form-input" value="{{ old('fullName', $driver->user->fullName) }}" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" id="password" name="password" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon *</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $driver->user->phone) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea id="address" name="address" class="form-textarea" rows="3">{{ old('address', $driver->user->address) }}</textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Informasi Kendaraan</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="vehicle_type" class="form-label">Jenis Kendaraan *</label>
                                <select id="vehicle_type" name="vehicle_type" class="form-select" required>
                                    <option value="">Pilih Jenis Kendaraan</option>
                                    <option value="Motor" {{ old('vehicle_type', $driver->vehicle_type) == 'Motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="Mobil" {{ old('vehicle_type', $driver->vehicle_type) == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="Sepeda" {{ old('vehicle_type', $driver->vehicle_type) == 'Sepeda' ? 'selected' : '' }}>Sepeda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="license_plate" class="form-label">Plat Nomor *</label>
                                <input type="text" id="license_plate" name="license_plate" class="form-input" value="{{ old('license_plate', $driver->license_plate) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="driver_license" class="form-label">Nomor SIM</label>
                                <input type="text" id="driver_license" name="driver_license" class="form-input" value="{{ old('driver_license', $driver->driver_license) }}">
                            </div>
                            <div class="form-group">
                                <label for="vehicle_registration" class="form-label">Nomor STNK</label>
                                <input type="text" id="vehicle_registration" name="vehicle_registration" class="form-input" value="{{ old('vehicle_registration', $driver->vehicle_registration) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Status Driver</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="status" class="form-label">Status *</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $driver->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="suspended" {{ old('status', $driver->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $driver->is_available) ? 'checked' : '' }}>
                                    Driver Tersedia
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Update Driver</button>
                        <a href="{{ route('drivers.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
