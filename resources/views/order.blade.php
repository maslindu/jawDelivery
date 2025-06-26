<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Status Pesanan</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content" data-order-status="{{ $order->status }}">

        <div class="waiting-container">
            <div class="status-section">
                <div class="status-icon" id="statusIcon">
                </div>
                <h1 class="status-title" id="statusTitle">Status Pesanan</h1>
                <p class="status-subtitle" id="statusSubtitle">Mohon tunggu update status pesanan Anda.</p>
            </div>

            <div class="invoice-card">
                <div class="invoice-header">
                    <h2 class="invoice-title">Detail Pesanan</h2>
                    <div class="invoice-number">
                        <span class="invoice-label">No. Invoice:</span>
                        <span class="invoice-id">#{{ $order->invoice }}</span>
                    </div>
                </div>

                <div class="invoice-body">
                    <div class="order-section">
                        <h3 class="section-title">Pesanan</h3>
                        <div class="order-items">
                            @foreach($order->menus as $menu)
                                <div class="order-item">
                                    <div class="item-info">
                                        <div class="product-image"
                                            style="background-image: url('{{ $menu->image_url }}');
                                            background-size: cover;
                                            background-position: center;">
                                        </div>
                                        <div class="item-infos">
                                            <span class="item-name">{{ $menu->name }}</span>
                                            <span class="item-qty">x{{ $menu->pivot->quantity }}</span>
                                        </div>
                                    </div>
                                    <span class="item-price">Rp {{ number_format($menu->price * $menu->pivot->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="payment-section">
                        <h3 class="section-title">Rincian Pembayaran</h3>
                        <div class="payment-details">
                            <div class="payment-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="payment-row">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="payment-row">
                                <span>Biaya Layanan</span>
                                <span>Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="payment-row total-row">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->subtotal + $order->shipping_fee + $order->admin_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Info -->
                    <div class="delivery-section">
                        <h3 class="section-title">Informasi Pengiriman</h3>
                        <div class="delivery-info">
                            <div class="info-row">
                                <span class="info-label">Alamat:</span>
                                <span class="info-value">{{ $order->address->address }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Metode Pembayaran:</span>
                                <span class="info-value">{{ $order->payment_method }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Waktu Pemesanan:</span>
                                <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            @if($order->notes)
                                <div class="info-row">
                                    <span class="info-label">Catatan:</span>
                                    <span class="info-value">{{ $order->notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Footer -->
                <div class="invoice-footer">
                    <div class="status-indicator">
                        <div class="status-dot" id="statusDot"></div>
                        <span class="status-text" id="statusText">Status Pesanan</span>
                    </div>
                    <p class="footer-note" id="footerNote">Hubungi customer service jika ada pertanyaan tentang pesanan Anda.</p>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/order.js') }}" defer></script>
</body>
</html>
