<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Orders</title>
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>

<body>
   @include('components.header')

   @php
   // Dummy data untuk menu items
   $menuItems = [
      ['name' => 'Nasi Goreng Spesial', 'image' => 'nasi-goreng.jpg'],
      ['name' => 'Ayam Bakar Madu', 'image' => 'ayam-bakar.jpg'],
      ['name' => 'Sate Ayam', 'image' => 'sate-ayam.jpg'],
      ['name' => 'Gado-gado', 'image' => 'gado-gado.jpg'],
      ['name' => 'Soto Ayam', 'image' => 'soto-ayam.jpg'],
      ['name' => 'Rendang Daging', 'image' => 'rendang.jpg'],
      ['name' => 'Es Teh Manis', 'image' => 'es-teh.jpg'],
      ['name' => 'Es Jeruk', 'image' => 'es-jeruk.jpg'],
      ['name' => 'Jus Alpukat', 'image' => 'jus-alpukat.jpg'],
      ['name' => 'Bakso Malang', 'image' => 'bakso.jpg'],
      ['name' => 'Mie Ayam', 'image' => 'mie-ayam.jpg'],
      ['name' => 'Pecel Lele', 'image' => 'pecel-lele.jpg']
   ];

   // Function to generate random order code
   function generateOrderCode()
   {
      return '#' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
   }

   // Function to get random menu items for an order
   function getRandomMenuItems($menuItems, $count = null)
   {
      $count = $count ?? rand(2, 5);
      $selectedItems = [];
      $shuffled = $menuItems;
      shuffle($shuffled);

      for ($i = 0; $i < $count && $i < count($shuffled); $i++) {
        $selectedItems[] = [
          'menu' => $shuffled[$i],
          'quantity' => rand(1, 3)
        ];
      }
      return $selectedItems;
   }

   // Dummy orders data
   $orders = [
      [
        'code' => generateOrderCode(),
        'status' => 'baru',
        'items' => getRandomMenuItems($menuItems, 3),
        'customer' => 'Ahmad Rizki'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'diproses',
        'items' => getRandomMenuItems($menuItems, 4),
        'customer' => 'Siti Nurhaliza'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'baru',
        'items' => getRandomMenuItems($menuItems, 2),
        'customer' => 'Budi Santoso'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'siap',
        'items' => getRandomMenuItems($menuItems, 3),
        'customer' => 'Maya Sari'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'diantar',
        'items' => getRandomMenuItems($menuItems, 5),
        'customer' => 'Andi Wijaya'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'diantar',
        'items' => getRandomMenuItems($menuItems, 2),
        'customer' => 'Rina Melati'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'selesai',
        'items' => getRandomMenuItems($menuItems, 4),
        'customer' => 'Dedi Kurniawan'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'selesai',
        'items' => getRandomMenuItems($menuItems, 3),
        'customer' => 'Lina Marlina'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'baru',
        'items' => getRandomMenuItems($menuItems, 2),
        'customer' => 'Hendra Gunawan'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'diproses',
        'items' => getRandomMenuItems($menuItems, 4),
        'customer' => 'Dewi Sartika'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'siap',
        'items' => getRandomMenuItems($menuItems, 3),
        'customer' => 'Rudi Hartono'
      ],
      [
        'code' => generateOrderCode(),
        'status' => 'diantar',
        'items' => getRandomMenuItems($menuItems, 2),
        'customer' => 'Indah Permata'
      ]
   ];

   // Count orders by status
   $statusCounts = [
      'baru' => 0,
      'diproses' => 0,
      'siap' => 0,
      'diantar' => 0,
      'selesai' => 0
   ];

   foreach ($orders as $order) {
      $statusCounts[$order['status']]++;
   }
@endphp

   <main class="main-content">
      <!-- Status Pesanan Section -->
      <section class="status-section">
         <h2 class="section-title">Status Pesanan</h2>
         <div class="status-grid">
            <div class="status-card">
               <div class="status-box status-baru" id="count-baru">{{ $statusCounts['baru'] }}</div>
               <span class="status-label">Baru</span>
            </div>
            <div class="status-card">
               <div class="status-box status-diproses" id="count-diproses">{{ $statusCounts['diproses'] }}</div>
               <span class="status-label">Diproses</span>
            </div>
            <div class="status-card">
               <div class="status-box status-siap" id="count-siap">{{ $statusCounts['siap'] }}</div>
               <span class="status-label">Siap</span>
            </div>
            <div class="status-card">
               <div class="status-box status-diantar" id="count-diantar">{{ $statusCounts['diantar'] }}</div>
               <span class="status-label">Diantar</span>
            </div>
            <div class="status-card">
               <div class="status-box status-selesai" id="count-selesai">{{ $statusCounts['selesai'] }}</div>
               <span class="status-label">Selesai</span>
            </div>
         </div>
      </section>

      <!-- Daftar Pesanan Section -->
      <section class="orders-section">
         <h2 class="section-title">Daftar Pesanan</h2>
         <div class="orders-grid">
            <div class="orders-grid">
               @foreach($orders as $index => $order)
                  @php
                  $orderDetailUrl = url('/admin/orders-detail/' . $order['code']);
                  $isAdmin = auth()->user()->hasRole('admin');
             @endphp

                  <div class="order-card clickable-card" data-order-id="{{ $index }}"
                    data-detail-url="{{ $isAdmin ? $orderDetailUrl : '' }}">
                    <div class="order-header">
                      <span class="order-code">{{ $order['code'] }}</span>
                      <span class="customer-name">{{ $order['customer'] }}</span>
                    </div>

                    <div class="order-content">
                      <div class="menu-photos-stack">
                        @foreach(array_slice($order['items'], 0, 3) as $photoIndex => $item)
                        <div class="photo-item" style="z-index: {{ 10 - $photoIndex }};
                       transform: translateX({{ $photoIndex * 15 }}px) translateY({{ $photoIndex * 10 }}px);">
                           <img src="/placeholder.svg?height=60&width=80" alt="{{ $item['menu']['name'] }}"
                            class="menu-photo">
                        </div>
                    @endforeach
                        @if(count($order['items']) > 3)
                      <div class="photo-count">+{{ count($order['items']) - 3 }}</div>
                    @endif
                      </div>

                      <div class="menu-items-list">
                        @foreach($order['items'] as $item)
                      <div class="menu-item">
                        {{ $item['menu']['name'] }}
                        <span class="quantity">x{{ $item['quantity'] }}</span>
                      </div>
                    @endforeach
                      </div>
                    </div>

                    <div class="status-container">
                      <button class="status-button status-button-{{ $order['status'] }}"
                        onclick="toggleStatusDropdown(this); event.stopPropagation();"
                        data-current-status="{{ $order['status'] }}">
                        {{ ucfirst($order['status']) }}
                      </button>
                      <div class="status-dropdown" style="display: none;" onclick="event.stopPropagation();">
                        <button class="status-option {{ $order['status'] == 'baru' ? 'active' : '' }}"
                           data-status="baru"
                           onclick="selectStatus(this, 'Baru'); event.stopPropagation();">Baru</button>
                        <button class="status-option {{ $order['status'] == 'diproses' ? 'active' : '' }}"
                           data-status="diproses"
                           onclick="selectStatus(this, 'Diproses'); event.stopPropagation();">Diproses</button>
                        <button class="status-option {{ $order['status'] == 'siap' ? 'active' : '' }}"
                           data-status="siap"
                           onclick="selectStatus(this, 'Siap'); event.stopPropagation();">Siap</button>
                        <button class="status-option {{ $order['status'] == 'diantar' ? 'active' : '' }}"
                           data-status="diantar"
                           onclick="selectStatus(this, 'Diantar'); event.stopPropagation();">Diantar</button>
                        <button class="status-option {{ $order['status'] == 'selesai' ? 'active' : '' }}"
                           data-status="selesai"
                           onclick="selectStatus(this, 'Selesai'); event.stopPropagation();">Selesai</button>
                      </div>
                    </div>
                  </div>
            @endforeach
            </div>

         </div>

      </section>
   </main>

   <script>
      // Klik pada card, kecuali tombol status
      document.querySelectorAll('.clickable-card').forEach(card => {
         card.addEventListener('click', function (e) {
            const target = e.target;

            // Cegah klik jika yang diklik adalah tombol status atau dropdown
            if (target.closest('.status-button') || target.closest('.status-dropdown')) {
               return;
            }

            // Pindah ke halaman detail (tanpa ID)
            window.location.href = '/admin/orders-detail';
         });
      });

   </script>


   <script>
      function toggleStatusDropdown(button) {
         const container = button.closest('.status-container');
         const dropdown = container.querySelector('.status-dropdown');

         // Close all other dropdowns
         document.querySelectorAll('.status-dropdown').forEach(d => {
            if (d !== dropdown) {
               d.style.display = 'none';
            }
         });

         // Toggle current dropdown
         if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
         } else {
            dropdown.style.display = 'none';
         }
      }

      function selectStatus(option, statusText) {
         const dropdown = option.closest('.status-dropdown');
         const container = option.closest('.order-card');
         const statusButton = container.querySelector('.status-button');
         const oldStatus = statusButton.dataset.currentStatus;
         const newStatus = option.dataset.status;

         // Don't update if same status
         if (oldStatus === newStatus) {
            dropdown.style.display = 'none';
            return;
         }

         // Remove active class from all options in this dropdown
         dropdown.querySelectorAll('.status-option').forEach(opt => {
            opt.classList.remove('active');
         });

         // Add active class to selected option
         option.classList.add('active');

         // Update button text and style
         statusButton.textContent = statusText;
         statusButton.className = 'status-button status-button-' + newStatus;
         statusButton.dataset.currentStatus = newStatus;

         // Update status counts
         updateStatusCounts(oldStatus, newStatus);

         // Hide dropdown
         dropdown.style.display = 'none';

         // Here you can add AJAX call to update status in database
         console.log('Status updated from', oldStatus, 'to', newStatus);
      }

      function updateStatusCounts(oldStatus, newStatus) {
         // Decrease old status count
         const oldCountElement = document.getElementById('count-' + oldStatus);
         const oldCount = parseInt(oldCountElement.textContent);
         oldCountElement.textContent = Math.max(0, oldCount - 1);

         // Increase new status count
         const newCountElement = document.getElementById('count-' + newStatus);
         const newCount = parseInt(newCountElement.textContent);
         newCountElement.textContent = newCount + 1;

         // Add animation effect
         [oldCountElement, newCountElement].forEach(element => {
            element.style.transform = 'scale(1.2)';
            element.style.transition = 'transform 0.3s ease';
            setTimeout(() => {
               element.style.transform = 'scale(1)';
            }, 300);
         });
      }

      // Close dropdowns when clicking outside
      document.addEventListener('click', function (event) {
         if (!event.target.closest('.status-container')) {
            document.querySelectorAll('.status-dropdown').forEach(dropdown => {
               dropdown.style.display = 'none';
            });
         }
      });
   </script>

   <script src="{{ asset('js/header.js') }}" defer></script>

</body>

</html>