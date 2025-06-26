<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>JawDelivery - Profile</title>
   <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   <link rel="stylesheet" href="{{ asset('css/history.css') }}">
   <link rel="stylesheet" href="{{ asset('css/components/popup-history.css') }}">
</head>

<body>
   @include('components.header')

   <div class="history-container">
      <h2 class="history-title">Riwayat Transaksi</h2>

    @php
        $statusColors = [
            'pending' => '#FFE082',    // Kuning - menunggu
            'processing' => '#FFD54F', // Kuning lebih gelap - sedang diproses
            'shipped' => '#81D4FA',    // Biru muda - dikirim
            'delivered' => '#4FC3F7',  // Biru - diterima
            'completed' => '#C8E6C9',  // Hijau - selesai
            'cancelled' => '#EF9A9A',  // Merah muda - dibatalkan
            'failed' => '#E57373',     // Merah - gagal
        ];

        $statusIcons = [
            'pending' => '🕒',     // menunggu
            'processing' => '👨‍🍳', // diproses
            'shipped' => '🚚',     // dikirim
            'delivered' => '📦',   // diterima
            'completed' => '✔️',   // selesai
            'cancelled' => '❌',   // dibatalkan
            'failed' => '⚠️',     // gagal
        ];

     @endphp

      @foreach($histories as $history)
        <div class="history-card">
          <div class="history-header">
            <div d>
               <div class="history-order-number">{{ $history['invoice'] }}</div>
               <div class="history-date">{{ date('d M Y', strtotime($history['date'])) }}</div>
               <div class="history-info">Jumlah Item: <b>{{ $history['items'] }}</b></div>
               <div class="history-info" style="margin-bottom:0;">Metode: <b>{{ $history['payment'] }}</b></div>
            </div>

            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
               <div class="history-status" style="
            background-color: {{ $statusColors[$history['status']] ?? '#ccc' }};
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;">
                 <span class="status-icon">{{ $statusIcons[$history['status']] ?? '❔' }}</span>
                 {{ $history['status'] }}
               </div>

               <button class="detail-btn" id="detailButton" data-id='{{ $history['id'] }}'>Detail</button>
            </div>
          </div>

          <div class="history-total-row" style="justify-content: flex-end;">
            <div class="history-total">
               Total: Rp{{ number_format($history['total'], 0, ',', '.') }}
            </div>
          </div>
        </div>

     @endforeach
   </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.detail-btn').forEach(function(button) {
            button.addEventListener('click', function () {
                const orderId = button.getAttribute('data-id');
                if (orderId) {
                    window.location.href = `/order/${orderId}`;
                }
            });
        });
    });
    </script>



   <script src="{{ asset('js/header.js') }}" defer></script>
</body>

</html>
