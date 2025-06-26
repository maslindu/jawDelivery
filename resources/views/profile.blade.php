<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Profile</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        <section class="profile-section">
            <h2 class="profile-title">Profil Anda</h2>
            @if(session('success') || session('error'))
                <div class="status-box {{ session('success') ? 'success' : 'error' }}">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif
            <div class="profile-content">
                <div class="photo-section">
                    <div class="photo-placeholder" id="photoPreview" style="background-image: url('{{ Auth::user()->avatar_url }}');
                                background-size: cover;
                                background-position: center;">
                    </div>
                    <button class="upload-btn" id="uploadBtn">Upload Foto</button>
                </div>

                <form class="form-section" action="{{ route('user.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" id="photoInput" name="avatar" accept="image/*"
                        style="visibility: hidden; width: 0;">
                    <div>
                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-input"
                                value="{{ Auth::user()->username }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="fullname">Nama Lengkap</label>
                            <input type="text" id="fullname" name="fullName" class="form-input"
                                value="{{ Auth::user()->fullName }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input"
                                value="{{ Auth::user()->email }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Nomor Telpon</label>
                            <input type="tel" id="phone" name="phone" class="form-input"
                                value="{{ Auth::user()->phone }}">
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="submit-btn">Submit Perubahan</button>
                            <button type="button" class="password-btn"
                                onclick="window.location.href='{{ route('password.change') }}'">
                                Ubah Password
                            </button>

                        </div>
                </form>
            </div>
        </section>

        @role('pelanggan')
            <div class="bottom-cards">
                <div class="card">
                    <h3 class="card-title">Alamat Anda</h3>
                    <div class="address-content">
                        <svg class="location-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="address-info">
                            @if($primaryAddress)
                                <div class="address-name">{{ $primaryAddress->label }}</div>
                                <div class="address-detail">{{ $primaryAddress->address }}</div>
                            @else
                                <div class="address-name text-muted">Belum ada alamat</div>
                                <div class="address-detail">Silakan tambah alamat</div>
                            @endif
                        </div>
                        <a href="{{ route('user.address') }}" class="action-link">Atur Alamat</a>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                    <div class="address-content">
                        @if ($firstOrder)
                            <div class="transaction-info">
                                <div class="transaction-date">{{ $firstOrder['date'] }}</div>
                                <div class="transaction-total">
                                    Total Pembayaran : Rp. {{ number_format($firstOrder['total'], 0, ',', '.') }}
                                </div>
                            </div>
                        @else
                            <div class="transaction-info">
                                Tidak ada transaksi
                            </div>
                        @endif
                        <a href="{{ route('user.history') }}" class="action-link" style="margin-bottom:12px">Lihat Seluruh
                        Transaksi</a>
                    </div>
                </div>
            </div>
        @endrole

    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script>
        let isFormChanged = false;
        let isSubmitting = false;

        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', () => {
                isFormChanged = true;
            });
        });

        document.querySelector('.submit-btn').addEventListener('click', () => {
            isSubmitting = true;
        });

        window.addEventListener('beforeunload', function (e) {
            if (isFormChanged && !isSubmitting) {
                e.preventDefault();
                e.returnValue = 'Perubahan yang belum disimpan akan hilang. Yakin ingin keluar atau me-refresh halaman?';
            }
        });
    </script>

    <script>
        const uploadBtn = document.getElementById('uploadBtn');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');

        uploadBtn.addEventListener('click', () => {
            photoInput.click();
        });

        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    photoPreview.style.backgroundImage = `url(${e.target.result})`;
                    photoPreview.style.backgroundSize = 'cover';
                    photoPreview.style.backgroundPosition = 'center';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>


</body>

</html>
