{{-- resources/views/components/popup-transaction.blade.php --}}
@props(['history'])

<div id="popupOverlay-{{ $history['order_number'] }}" class="popup-overlay" style="display: none;"></div>
<div id="popupTransaction-{{ $history['order_number'] }}" class="popup-container" style="display: none;">
   <div class="popup-card">
      <div class="status-box-container">
         <div class="status-box">
            <div class="check-icon">&#10004;</div>
            <div class="status-text">
               <div class="status-label">Pesanan {{ $history['status'] }}</div>
               <div class="order-id">Order {{ $history['order_number'] }}</div>
            </div>
         </div>
      </div>

      <div class="section">
         <h4>Item Pesanan</h4>

         @php
         $items = [
            ['name' => 'Nasi Goreng', 'qty' => 1, 'price' => 15000, 'note' => 'tanpa gula'],
            ['name' => 'Ayam Bakar', 'qty' => 2, 'price' => 30000, 'note' => 'pedas'],
         ];
       @endphp

         @foreach ($items as $item)
          <div class="item-row boxed-item">
            <div class="image-box"></div>
            <div class="item-details">
               <div class="item-name">{{ $item['name'] }}</div>
               <div class="item-qty">{{ $item['qty'] }}x</div>
               <div class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
               <div class="catatan">Catatan: {{ $item['note'] }}</div>
            </div>
          </div>
       @endforeach

         <div class="total-row">
            <span>Total</span>
            <strong class="text-green">Rp {{ number_format($history['total'], 0, ',', '.') }}</strong>
         </div>
      </div>

      <div class="section">
         <h4>Informasi Pengiriman</h4>
         <div class="boxed-item">
            <p><i class="icon">👤</i> {{ $history['buyer'] }}</p>
            <p><i class="icon">📞</i> 0706794873</p>
            <p><i class="icon">📍</i> jl sini</p>
         </div>
      </div>

      <div class="section">
         <h4>Informasi Pembayaran</h4>
         <div class="boxed-item">
            <p>Metode Pembayaran: <strong>{{ $history['payment'] }}</strong></p>
            <p>Status Pembayaran: <span class="status-label" style="background: {{ $history['status'] == 'Selesai' ? '#C8E6C9' : ($history['status'] == 'Diproses' ? '#FFE082' : '#EF9A9A') }};
                color: black; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 13px;">
                  Lunas
               </span></p>
            <p>Tanggal Pesanan: {{ date('d/m/Y H:i', strtotime($history['date'])) }}</p>
            <p>Tanggal Selesai: {{ date('d/m/Y H:i', strtotime($history['date'] . ' +1 minute')) }}</p>
         </div>
      </div>


      @if(strtolower($history['payment']) == 'transfer')
        <div class="section">
          <h4>Bukti Pembayaran</h4>
          <div class="image-box full-width"></div>
        </div>
     @else
        <div class="section">
          <h4>Bukti Pembayaran</h4>
          <div class="cod-box">
            COD (Cash on Delivery)
          </div>
        </div>
     @endif

      <button onclick="closePopup('{{ $history['order_number'] }}')" class="btn-tutup">Tutup</button>
   </div>
</div>