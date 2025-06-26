<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Edit Menu</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/edit-menu.css') }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1>Edit Menu: {{ $menu->name }}</h1>
                <a href="{{ route('admin.manage-menu') }}" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="menu-form">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Menu <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $menu->name) }}" 
                            class="form-input @error('name') error @enderror"
                            required
                            maxlength="255"
                        >
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga <span class="required">*</span></label>
                        <input 
                            type="number" 
                            id="price" 
                            name="price" 
                            value="{{ old('price', $menu->price) }}" 
                            class="form-input @error('price') error @enderror"
                            min="0" 
                            step="100" 
                            required
                        >
                        <small class="form-help">Masukkan harga dalam Rupiah</small>
                        @error('price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stok <span class="required">*</span></label>
                        <input 
                            type="number" 
                            id="stock" 
                            name="stock" 
                            value="{{ old('stock', $menu->stock) }}" 
                            class="form-input @error('stock') error @enderror"
                            min="0" 
                            required
                        >
                        @error('stock')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="categories">Kategori</label>
                        <select id="categories" name="categories[]" multiple class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ in_array($category->id, old('categories', $menu->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-help">Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih beberapa kategori</small>
                        @error('categories')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        class="form-textarea @error('description') error @enderror"
                        placeholder="Masukkan deskripsi menu (opsional)"
                        maxlength="1000"
                    >{{ old('description', $menu->description) }}</textarea>
                    <small class="form-help">Maksimal 1000 karakter</small>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar Menu</label>
                    
                    @if($menu->image_link)
                        <div class="current-image">
                            <p class="current-image-label">Gambar saat ini:</p>
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="current-image-preview">
                        </div>
                    @endif
                    
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="form-input @error('image') error @enderror"
                    >
                    <small class="form-help">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                    
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImg" src="#" alt="Preview" style="display: none;">
                    </div>
                    
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="button" onclick="window.history.back()" class="btn-cancel">
                        Batal
                    </button>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17,21 17,13 7,13 7,21"/>
                            <polyline points="7,3 7,8 15,8"/>
                        </svg>
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImg');
            
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPEG, PNG, JPG, atau GIF.');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Auto format price input
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = value;
        });

        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const maxLength = 1000;
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

        // Auto hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Form validation before submit
        document.querySelector('.menu-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = document.getElementById('price').value;
            const stock = document.getElementById('stock').value;
            
            if (!name) {
                alert('Nama menu harus diisi!');
                e.preventDefault();
                return;
            }
            
            if (!price || price < 0) {
                alert('Harga harus diisi dan tidak boleh negatif!');
                e.preventDefault();
                return;
            }
            
            if (!stock || stock < 0) {
                alert('Stok harus diisi dan tidak boleh negatif!');
                e.preventDefault();
                return;
            }
        });
    </script>
    <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>
