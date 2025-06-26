<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Tambah Menu</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>

<body>
   @include('components.header')

   <main class="main-content">
      <div class="form-container">
         <div class="form-header">
            <h1>Tambah Menu Baru</h1>
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

         <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="menu-form">
            @csrf
            
            <div class="form-row">
               <div class="form-group">
                  <label for="name">Nama Menu *</label>
                  <input type="text" id="name" name="name" value="{{ old('name') }}" required>
               </div>
               
               <div class="form-group">
                  <label for="price">Harga *</label>
                  <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="100" required>
               </div>
            </div>

            <div class="form-row">
               <div class="form-group">
                  <label for="stock">Stok *</label>
                  <input type="number" id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
               </div>
               
               <div class="form-group">
                  <label for="categories">Kategori</label>
                  <select id="categories" name="categories[]" multiple>
                     @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                           {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                           {{ $category->name }}
                        </option>
                     @endforeach
                  </select>
                  <small>Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih beberapa kategori</small>
               </div>
            </div>

            <div class="form-group">
               <label for="description">Deskripsi</label>
               <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
               <label for="image">Gambar Menu</label>
               <input type="file" id="image" name="image" accept="image/*">
               <div class="image-preview" id="imagePreview">
                  <img id="previewImg" src="#" alt="Preview" style="display: none;">
               </div>
            </div>

            <div class="form-actions">
               <button type="button" onclick="window.history.back()" class="btn-cancel">Batal</button>
               <button type="submit" class="btn-submit">Tambah Menu</button>
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
   </script>
   <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>