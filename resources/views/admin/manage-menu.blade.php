<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Kelola Menu</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/manage-menu.css') }}">
</head>

<body>
   @include('components.header')

   <main class="main-content">
      <h1 class="section-title">Kelola Menu & Kategori</h1>

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
            <!-- Konten kategori (opsional) -->
         </div>
      </div>

      <div class="box-container">
         <div class="box-header">
            <h2>Kelola Menu</h2>
            <a href="/admin/add-menu" class="add-menu-button">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" stroke="#000" stroke-width="2" stroke-linecap="round" />
               </svg>
               Tambah Menu Baru
            </a>
         </div>
         <div class="menu-grid">
            @for ($i = 0; $i < 8; $i++)
            <div class="menu-card">
               <div class="menu-image"></div>
               <div class="menu-category">Kategori Menu</div>
               <div class="menu-name">Nama Menu</div>
               <div class="menu-price">Rp. 10.000</div>
               <div class="menu-action" onclick="toggleMenuOptions(this)">
                 <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                   viewBox="0 0 24 24">
                   <path
                     d="M12 8a2 2 0 110-4 2 2 0 010 4zm0 2a2 2 0 110 4 2 2 0 010-4zm0 6a2 2 0 110 4 2 2 0 010-4z" />
                 </svg>
                 <div class="menu-action-options">
                   <button>Edit</button>
                   <button class="delete">Hapus</button>
                 </div>
               </div>
            </div>
         @endfor
         </div>
      </div>
   </main>

   <script>
      function toggleMenuOptions(el) {
         document.querySelectorAll('.menu-action-options').forEach(opt => {
            if (opt !== el.querySelector('.menu-action-options')) {
               opt.classList.remove('show');
            }
         });
         const options = el.querySelector('.menu-action-options');
         options.classList.toggle('show');
      }

      window.addEventListener('click', function (e) {
         if (!e.target.closest('.menu-action')) {
            document.querySelectorAll('.menu-action-options').forEach(opt => opt.classList.remove('show'));
         }
      });
   </script>
   <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>