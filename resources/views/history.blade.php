<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Profile</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/history.css') }}">
   <style>
      .status-icon {
         margin-right: 8px;
         font-weight: bold;
      }
   </style>
</head>

<body>
   @include('components.header')

   <div class="history-container">
      <h2 class="history-title">Riwayat Transaksi</h2>

      @php
         $histories = [
            [
               'order_number' => 'INV-20240601-001',
               'date' => '2024-06-01',
               'items' => 3,
               'buyer' => 'Budi Santoso',
               'payment' => 'Tunai',
               'status' => 'Selesai',
               'total' => 120000,
            ],
            [
               'order_number' => 'INV-20240601-002',
               'date' => '2024-06-01',
               'items' => 2,
               'buyer' => 'Siti Aminah',
               'payment' => 'Transfer',
               'status' => 'Diproses',
               'total' => 85000,
            ],
            [
               'order_number' => 'INV-20240601-003',
               'date' => '2024-06-01',
               'items' => 1,
               'buyer' => 'Andi Wijaya',
               'payment' => 'Tunai',
               'status' => 'Gagal',
               'total' => 50000,
            ],
         ];

         $statusColors = [
            'Diproses' => '#FFE082',
            'Selesai' => '#C8E6C9',
            'Gagal' => '#EF9A9A',
         ];

         $statusIcons = [
            'Selesai' => '✔️',
            'Diproses' => '🕒',
            'Gagal' => '❌',
         ];
      @endphp

      @foreach($histories as $history)
         <div class="history-card">
            <div class="history-header">
               <div>
                  <div class="history-order-number">{{ $history['order_number'] }}</div>
                  <div class="history-date">{{ date('d M Y', strtotime($history['date'])) }}</div>
                  <div class="history-info">Jumlah Item: <b>{{ $history['items'] }}</b></div>
                  <div class="history-info">Pembeli: <b>{{ $history['buyer'] }}</b></div>
                  <div class="history-info" style="margin-bottom:0;">Metode: <b>{{ $history['payment'] }}</b></div>
               </div>

               <div class="history-status"
                    style="background-color: {{ $statusColors[$history['status']] ?? '#ccc' }};">
                  <span class="status-icon">{{ $statusIcons[$history['status']] ?? '❔' }}</span>
                  {{ $history['status'] }}
               </div>
            </div>

            <div class="history-total-row">
               <div class="history-total">
                  Total: Rp{{ number_format($history['total'], 0, ',', '.') }}
               </div>
            </div>
         </div>
      @endforeach

   </div>

   <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
