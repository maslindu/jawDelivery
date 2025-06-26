<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Tambah Kategori</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/add-category.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1>Tambah Kategori Baru</h1>
                <a href="{{ route('admin.manage-menu') }}" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Kembali
                </a>
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

            <form action="{{ route('admin.category.store') }}" method="POST" class="category-form">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Nama Kategori <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input @error('name') error @enderror" 
                        value="{{ old('name') }}" 
                        placeholder="Masukkan nama kategori"
                        required
                        maxlength="255"
                    >
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-textarea @error('description') error @enderror" 
                        placeholder="Masukkan deskripsi kategori (opsional)"
                        rows="4"
                        maxlength="500"
                    >{{ old('description') }}</textarea>
                    <small class="form-help">Maksimal 500 karakter</small>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="button" onclick="window.history.back()" class="btn-cancel">
                        Batal
                    </button>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        Tambah Kategori
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const maxLength = 500;
                const currentLength = this.value.length;
                const helpText = this.parentNode.querySelector('.form-help');
                
                if (helpText) {
                    helpText.textContent = `${currentLength}/${maxLength} karakter`;
                    
                    if (currentLength > maxLength * 0.9) {
                        helpText.style.color = '#e74c3c';
                    } else {
                        helpText.style.color = '#6c757d';
                    }
                }
            });
        }
    </script>
    <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>
