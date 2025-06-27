<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Kelola Menu</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/manage-menu.css') }}">
   <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
   @include('components.header')

   <main class="main-content">
      <h1 class="section-title">Kelola Kategori & Menu</h1>

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

      <div class="box-container">
         <div class="box-header">
            <h2>Kelola Kategori</h2>
            <a href="/admin/add-category" class="add-category-button">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" stroke="#000" stroke-width="2" stroke-linecap="round" />
               </svg>
               Tambah Kategori Baru
            </a>
         </div>
         <div class="category-list">
            @if(isset($categories) && $categories->count() > 0)
               <div class="category-grid">
                  @foreach($categories as $category)
                     <div class="category-item">
                        <span class="category-name">{{ $category->name }}</span>
                        <div class="category-actions">
                           <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')">Edit</button>
                           <button onclick="deleteCategory({{ $category->id }})" class="delete">Hapus</button>
                        </div>
                     </div>
                  @endforeach
               </div>
            @else
               <p class="empty-state">Belum ada kategori. Tambahkan kategori pertama Anda!</p>
            @endif
         </div>
      </div>

      <div class="box-container">
         <div class="box-header">
            <h2>Kelola Menu</h2>
            <a href="{{ route('admin.menu.create') }}" class="add-menu-button">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" stroke="#000" stroke-width="2" stroke-linecap="round" />
               </svg>
               Tambah Menu Baru
            </a>
         </div>
         <div class="menu-grid">
            @if($menuItems->count() > 0)
               @foreach($menuItems as $menu)
               <div class="menu-card">
                  <div class="menu-image" style="background-image: url('{{ $menu->image_url }}'); background-size: cover; background-position: center;"></div>
                  <div class="menu-category">{{ $menu->category_names ?: 'Tanpa Kategori' }}</div>
                  <div class="menu-name">{{ $menu->name }}</div>
                  <div class="menu-price">{{ $menu->price_formatted }}</div>
                  <div class="menu-stock">Stok: {{ $menu->stock }}</div>
                  <div class="menu-action" onclick="toggleMenuOptions(this)">
                     <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                           d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                     </svg>
                     <div class="menu-action-options">
                        <a href="{{ route('admin.menu.edit', $menu->id) }}">Edit</a>
                        <button onclick="deleteMenu({{ $menu->id }}, '{{ $menu->name }}')" class="delete">Hapus</button>
                     </div>
                  </div>
               </div>
               @endforeach
            @else
               <div class="empty-state-menu">
                  <p>Belum ada menu. Tambahkan menu pertama Anda!</p>
               </div>
            @endif
         </div>
      </div>
   </main>

   <!-- Delete Confirmation Modal -->
   <div id="deleteModal" class="modal">
      <div class="modal-content">
         <h3>Konfirmasi Hapus</h3>
         <p id="deleteMessage"></p>
         <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-cancel">Batal</button>
            <button onclick="confirmDelete()" class="btn-delete">Hapus</button>
         </div>
      </div>
   </div>

   <script>
      let deleteTarget = null;
      let deleteType = null;

      function toggleMenuOptions(el) {
         document.querySelectorAll('.menu-action-options').forEach(opt => {
            if (opt !== el.querySelector('.menu-action-options')) {
               opt.classList.remove('show');
            }
         });
         const options = el.querySelector('.menu-action-options');
         options.classList.toggle('show');
      }

      function deleteMenu(id, name) {
         deleteTarget = id;
         deleteType = 'menu';
         document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus menu "${name}"?`;
         document.getElementById('deleteModal').style.display = 'flex';
      }

      function deleteCategory(id) {
         deleteTarget = id;
         deleteType = 'category';
         document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus kategori ini?';
         document.getElementById('deleteModal').style.display = 'flex';
      }

      function closeDeleteModal() {
         document.getElementById('deleteModal').style.display = 'none';
         deleteTarget = null;
         deleteType = null;
      }

      function confirmDelete() {
         if (deleteTarget && deleteType) {
            const form = document.createElement('form');
            form.method = 'POST';
            
            if (deleteType === 'menu') {
               form.action = `/admin/menu/${deleteTarget}`;
            } else {
               form.action = `/admin/category/${deleteTarget}`;
            }

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
         }
      }

      function editCategory(id, name) {
         // Implement edit category functionality
         const newName = prompt('Edit nama kategori:', name);
         if (newName && newName !== name) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/category/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';

            const nameField = document.createElement('input');
            nameField.type = 'hidden';
            nameField.name = 'name';
            nameField.value = newName;

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(nameField);
            document.body.appendChild(form);
            form.submit();
         }
      }

      window.addEventListener('click', function (e) {
         if (!e.target.closest('.menu-action')) {
            document.querySelectorAll('.menu-action-options').forEach(opt => opt.classList.remove('show'));
         }
      });

      // Auto hide alerts
      setTimeout(() => {
         const alerts = document.querySelectorAll('.alert');
         alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
         });
      }, 5000);
   </script>
   <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>