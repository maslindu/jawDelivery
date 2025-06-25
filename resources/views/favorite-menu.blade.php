<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Menu Favorit</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
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
         align-items: center;
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

   @php
      $favorites = [
         (object)[
            'id' => 1,
            'name' => 'Nasi Goreng Spesial',
            'price' => 25000,
            'image' => 'menu/nasi-goreng.jpg'
         ],
         (object)[
            'id' => 2,
            'name' => 'Ayam Bakar Madu',
            'price' => 32000,
            'image' => 'menu/ayam-bakar.jpg'
         ],
         (object)[
            'id' => 3,
            'name' => 'Es Teh Manis',
            'price' => 7000,
            'image' => 'menu/es-teh.jpg'
         ],
      ];
   @endphp

   <div class="favorite-container">
      <h2 class="favorite-title">Menu Favorit Anda</h2>
      <div class="menu-list">
         @foreach($favorites as $menu)
            <div class="menu-card">
                  <div style="width:160px;height:120px;background:#ececec;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#bbb;font-size:14px;">
                    Gambar Menu
                  </div>
               <h3>{{ $menu->name }}</h3>
               <div class="price">
                  Rp{{ number_format($menu->price, 0, ',', '.') }}
               </div>
               <button class="remove-btn">Hapus</button>
            </div>
         @endforeach
      </div>
   </div>

   <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
