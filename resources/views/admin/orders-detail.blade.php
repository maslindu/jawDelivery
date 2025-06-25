<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Profile</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/orders-detail.css') }}">
</head>

<body>
   @include('components.header')
   @php
   // Dummy data untuk order detail
   $order = [
      'id' => '#A7B2C3D4E5F6',
      'customer' => [
        'name' => 'Ahmad Rizki Pratama',
        'phone' => '081234567890',
        'address' => 'Jl. Surakarta Timur No. 999, Kecamatan Banjarsari, Surakarta, Jawa Tengah 57138'
      ],
      'status' => 'diproses',
      'created_at' => '2024-01-15 14:30:00',
      'notes' => 'tolong jangan a, tolong jangan b, toloooooogggg',
      'items' => [
        [
          'id' => 1,
          'name' => 'Nasi Goreng Spesial',
          'description' => 'Nasi goreng dengan telur, ayam, dan sayuran segar',
          'price' => 25000,
          'quantity' => 2,
          'image' => 'nasi-goreng.jpg'
        ],
        [
          'id' => 2,
          'name' => 'Ayam Bakar Madu',
          'description' => 'Ayam bakar dengan bumbu madu dan rempah pilihan',
          'price' => 35000,
          'quantity' => 1,
          'image' => 'ayam-bakar.jpg'
        ],
        [
          'id' => 3,
          'name' => 'Es Teh Manis',
          'description' => 'Minuman teh manis segar dengan es batu',
          'price' => 8000,
          'quantity' => 3,
          'image' => 'es-teh.jpg'
        ],
        [
          'id' => 4,
          'name' => 'Sate Ayam',
          'description' => 'Sate ayam dengan bumbu kacang khas',
          'price' => 20000,
          'quantity' => 2,
          'image' => 'sate-ayam.jpg'
        ]
      ]
   ];

   // Calculate total
   $total = 0;
   foreach ($order['items'] as $item) {
      $total += $item['price'] * $item['quantity'];
   }

   // Status options
   $statusOptions = [
      'baru' => ['label' => 'Pesanan Baru', 'color' => 'status-baru', 'icon' => '📝'],
      'diproses' => ['label' => 'Sedang Diproses', 'color' => 'status-diproses', 'icon' => '👨‍🍳'],
      'siap' => ['label' => 'Siap Diantar', 'color' => 'status-siap', 'icon' => '✅'],
      'diantar' => ['label' => 'Sedang Diantar', 'color' => 'status-diantar', 'icon' => '🚗'],
      'selesai' => ['label' => 'Pesanan Selesai', 'color' => 'status-selesai', 'icon' => '🎉']
   ];
@endphp

   <main class="main-content">
      <div class="details-container">
         <div class="order-section">
            <div class="order-header">
               <h2 class="section-title">Pesanan {{ $order['id'] }}</h2>
               <div class="order-meta">
                  <span class="order-date">{{ date('d M Y, H:i', strtotime($order['created_at'])) }}</span>
               </div>
            </div>

            <div class="order-items">
               @foreach($order['items'] as $item)
               <div class="order-item">
                 <div class="item-details">
                   <h3 class="item-name">{{ $item['name'] }}</h3>
                   <p class="item-description">{{ $item['description'] }}</p>
                 </div>
                 <div class="item-quantity">
                   <span class="quantity-badge">{{ $item['quantity'] }}</span>
                 </div>
                 <div class="item-price">
                   <span class="price">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                   <span class="subtotal">Rp
                     {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                 </div>
               </div>
            @endforeach
            </div>

            <div class="order-total">
               <div class="total-row">
                  <span class="total-label">Total Pesanan:</span>
                  <span class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
               </div>
            </div>

            <div class="order-notes">
               <h3 class="notes-title">Catatan</h3>
               <div class="notes-content">
                  {{ $order['notes'] }}
               </div>
            </div>
         </div>

         <div class="status-section">
            <h2 class="section-title">STATUS</h2>
            <div class="status-container">
               <div class="current-status">
                  <div class="status-icon">{{ $statusOptions[$order['status']]['icon'] }}</div>
                  <div class="status-info">
                     <h3 class="status-title">{{ $statusOptions[$order['status']]['label'] }}</h3>
                     <p class="status-time">Diperbarui {{ date('H:i', strtotime($order['created_at'])) }}</p>
                  </div>
               </div>

               <div class="status-progress">
                  @foreach($statusOptions as $key => $status)
                 <div
                   class="progress-step {{ $key == $order['status'] ? 'active' : '' }} {{ array_search($key, array_keys($statusOptions)) < array_search($order['status'], array_keys($statusOptions)) ? 'completed' : '' }}">
                   <div class="step-icon">{{ $status['icon'] }}</div>
                   <div class="step-label">{{ $status['label'] }}</div>
                 </div>
              @endforeach
               </div>

               <div class="status-actions">
                  <h4 class="actions-title">Ubah Status</h4>
                  <div class="status-buttons">
                     @foreach($statusOptions as $key => $status)
                   <button class="status-btn {{ $key == $order['status'] ? 'active' : '' }} {{ $status['color'] }}"
                     onclick="updateOrderStatus('{{ $key }}', '{{ $status['label'] }}')" data-status="{{ $key }}">
                     <span class="btn-icon">{{ $status['icon'] }}</span>
                     <span class="btn-text">{{ $status['label'] }}</span>
                   </button>
                @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="address-section">
         <h2 class="section-title">Alamat Pengiriman</h2>
         <div class="address-content">
            <h3 class="customer-name">{{ $order['customer']['name'] }}</h3>
            <p class="customer-phone">{{ $order['customer']['phone'] }}</p>
            <p class="customer-address">{{ $order['customer']['address'] }}</p>
         </div>
      </div>
   </main>

   <script>
      function updateOrderStatus(newStatus, statusLabel) {
         // Remove active class from all buttons
         document.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('active');
         });

         // Add active class to selected button
         document.querySelector(`[data-status="${newStatus}"]`).classList.add('active');

         // Update current status display
         const statusOptions = {
            'baru': { label: 'Pesanan Baru', icon: '📝' },
            'diproses': { label: 'Sedang Diproses', icon: '👨‍🍳' },
            'siap': { label: 'Siap Diantar', icon: '✅' },
            'diantar': { label: 'Sedang Diantar', icon: '🚗' },
            'selesai': { label: 'Pesanan Selesai', icon: '🎉' }
         };

         document.querySelector('.status-icon').textContent = statusOptions[newStatus].icon;
         document.querySelector('.status-title').textContent = statusOptions[newStatus].label;
         document.querySelector('.status-time').textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

         // Update progress steps
         const steps = document.querySelectorAll('.progress-step');
         const statusKeys = Object.keys(statusOptions);
         const currentIndex = statusKeys.indexOf(newStatus);

         steps.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index === currentIndex) {
               step.classList.add('active');
            } else if (index < currentIndex) {
               step.classList.add('completed');
            }
         });

         // Here you can add AJAX call to update status in database
         console.log('Status updated to:', newStatus);

         // Show success message
         showNotification('Status berhasil diperbarui ke: ' + statusLabel);
      }

      function showNotification(message) {
         // Create notification element
         const notification = document.createElement('div');
         notification.className = 'notification success';
         notification.textContent = message;

         // Add to page
         document.body.appendChild(notification);

         // Show notification
         setTimeout(() => {
            notification.classList.add('show');
         }, 100);

         // Hide and remove notification
         setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
               document.body.removeChild(notification);
            }, 300);
         }, 3000);
      }
   </script>

   <script src="{{ asset('js/header.js') }}" defer></script>

</body>

</html>