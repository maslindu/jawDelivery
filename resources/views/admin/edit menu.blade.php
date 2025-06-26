<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Edit Menu</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/manage-menu.css') }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        <h1 class="section-title">Edit Menu</h1>

        <div class="box-container">
            <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama Menu</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $menu->price) }}" required>
                    @error('price')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stok</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $menu->stock) }}" required>
                    @error('stock')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description">{{ old('description', $menu->description) }}</textarea>
                    @error('description')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="categories">Kategori</label>
                    <select name="categories[]" id="categories" multiple>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ in_array($category->id, old('categories', $menu->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('categories')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                @if($menu->image_link)
                    <div class="form-group">
                        <label>Gambar Saat Ini</label>
                        <img src="{{ asset('storage/' . $menu->image_link) }}" alt="{{ $menu->name }}" style="max-width: 200px; height: auto; border-radius: 8px;">
                    </div>
                @endif

                <div class="form-group">
                    <label for="image">Gambar Menu Baru (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    @error('image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $menu->featured) ? 'checked' : '' }}>
                        Menu Unggulan
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Update Menu</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
