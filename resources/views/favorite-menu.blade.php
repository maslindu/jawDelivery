<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>JawDelivery - Menu Favorit</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menupopup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
   <style>
      body {
         font-family: 'Plus Jakarta Sans', sans-serif;
         background: #f9f9f9;
         margin: 0;
         padding: 0;
      }
      .favorite-title {
         border-bottom: 2px solid #ff4d4f;
         padding-bottom: 6px;
         margin-bottom: 24px;
         font-size: 1.4rem;
         font-weight: bold;
         display: inline-block;
         }
      .favorite-container {
         max-width: 900px;
         margin: 40px auto;
         padding: 20px;
         border-radius: 18px;
         background: #fff;
         box-sizing: border-box;
         border: 1px solid #000;
      }
      .menu-list {
         display: flex;
         flex-wrap: wrap;
         gap: 24px;
      }
      .menu-card {
         background: #fff;
         border-radius: 12px;
         box-shadow: 0 2px 8px rgba(0,0,0,0.07);
         width: 220px;
         overflow: hidden;
         display: flex;
         flex-direction: column;
         align-menus: center;
         padding: 16px;
         transition: transform 0.2s ease;
      }
      .menu-card:hover {
         transform: translateY(-4px);
      }
      .menu-card img {
         width: 160px;
         height: 120px;
         object-fit: cover;
         border-radius: 8px;
      }
      .menu-card h3 {
         margin: 12px 0 6px 0;
         font-size: 1.1rem;
         text-align: center;
      }
      .menu-card .price {
         color: black;
         font-weight: bold;
         margin-bottom: 10px;
         font-size: 14px;
      }
      .remove-btn {
         background: #ff4d4f;
         color: #fff;
         border: none;
         border-radius: 6px;
         padding: 6px 16px;
         cursor: pointer;
         font-size: 0.95rem;
         transition: background 0.2s;
      }
      .remove-btn:hover {
         background: #d9363e;
      }
   </style>
</head>
<body>
   @include('components.header')
    @include('components.menupopup')
    @include('components.notification')

   <div class="favorite-container">
      <h2 class="favorite-title">Menu Favorit Anda</h2>
      <div class="menu-list">
         @foreach($menus as $menu)
            <div class="menu-card" id='menuItem'
                data-id="{{ $menu->id }}"
                data-name="{{ $menu->name }}"
                data-price="{{ $menu->price }}"
                data-stock="{{ $menu->stock }}"
                data-description="{{ $menu->description }}"
                data-categories="{{ $menu->categories->pluck('name')->implode(', ') }}"
                data-image-url="{{ $menu->image_url }}"
                data-is-fav="1">
                  <div style="
                        width:160px;
                        height:120px;
                        background:#ececec;
                        border-radius:8px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        color:#bbb;
                        font-size:14px;
                        background-image: url('{{ $menu->image_url }}');
                        background-size: cover;
                        background-position: center;
                  ">
                  </div>
               <h3>{{ $menu->name }}</h3>
               <div class="price">
                  Rp{{ number_format($menu->price, 0, ',', '.') }}
               </div>
               <button class="remove-btn" data-id='{{ $menu->id }}'>Hapus</button>
            </div>
         @endforeach
      </div>
   </div>

   <script src="{{ asset('js/header.js') }}" defer></script>
   <script src="{{ asset('js/menupopup.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const removeButtons = document.querySelectorAll('.remove-btn');

            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const menuId = button.dataset.id;

                    fetch(`/favorites?menu_id=${menuId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to remove favorite');
                        button.closest('.menu-card')?.remove();
                     })
                    .catch(console.error);
                });
            });
        });
    </script>

</body>
</html>
