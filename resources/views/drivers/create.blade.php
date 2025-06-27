<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Tambah Driver</title>
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
                    <h2 class="section-title">Tambah Driver Baru</h2>
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

                <form action="{{ route('drivers.store') }}" method="POST" class="driver-form">
                    @csrf

                    <div class="form-section">
                        <h3 class="form-section-title">Informasi Pengguna</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullName" class="form-label">Nama Lengkap *</label>
                            <input type="text" id="fullName" name="fullName" class="form-input" value="{{ old('fullName') }}" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" id="password" name="password" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon *</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea id="address" name="address" class="form-textarea" rows="3">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Informasi Kendaraan</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="vehicle_type" class="form-label">Jenis Kendaraan *</label>
                                <select id="vehicle_type" name="vehicle_type" class="form-select" required>
                                    <option value="">Pilih Jenis Kendaraan</option>
                                    <option value="Motor" {{ old('vehicle_type') == 'Motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="Mobil" {{ old('vehicle_type') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="Sepeda" {{ old('vehicle_type') == 'Sepeda' ? 'selected' : '' }}>Sepeda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="license_plate" class="form-label">Plat Nomor *</label>
                                <input type="text" id="license_plate" name="license_plate" class="form-input" value="{{ old('license_plate') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="driver_license" class="form-label">Nomor SIM</label>
                                <input type="text" id="driver_license" name="driver_license" class="form-input" value="{{ old('driver_license') }}">
                            </div>
                            <div class="form-group">
                                <label for="vehicle_registration" class="form-label">Nomor STNK</label>
                                <input type="text" id="vehicle_registration" name="vehicle_registration" class="form-input" value="{{ old('vehicle_registration') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Tambah Driver</button>
                        <a href="{{ route('drivers.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
