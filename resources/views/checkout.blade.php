<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Checkout Pesanan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paymentpopup.css') }}">
</head>

<body>
    @include('components.header')
    @include('components.paymentpopup')

    <main class="main-content">
        <div class="order-section">
            <div>
            <h2 class="section-title">Pesanan</h2>
            @foreach ($cartItems as $item)
                <div class="product-item" data-id="{{ $item->id }}">
                    <div class="product-image"
                        style="background-image: url('{{ $item->menu->image_url }}');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;">
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $item->menu->name }}</div>
                        <div class="product-description">{{ $item->menu->description }}</div>
                        <div class="product-stock">Stock: {{ $item->menu->stock }}</div>
                    </div>
                    <div class="product-price">Rp {{ number_format($item->menu->price, 0, ',', '.') }}</div>
                    <div class="quantity-controls">
                        <button class="quantity-btn decrement" data-id="{{ $item->id }}" data-stock="{{ $item->menu->stock }}">-</button>
                        <span class="quantity-display">{{ $item->quantity }}</span>
                        <button class="quantity-btn increment" data-id="{{ $item->id }}" data-stock="{{ $item->menu->stock }}">+</button>
                    </div>
                    <button class="delete-btn" data-id="{{ $item->id }}">🗑</button>
                </div>
            @endforeach
            </div>


            <div class="order-total">
                <span class="total-text">Total Pesanan :  Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                <textarea id="order-notes" name="notes" class="notes-input" placeholder="Masukkan catatan untuk pesanan..." rows="2" style="padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc;"></textarea>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">

            <!-- Box 1: Address Section -->
            <div class="summary-card address-card">
                <div class="address-section">
                    <div class="address-info">
                        <div class="address-title">Alamat Pengiriman</div>
                        <div class="address-details">
                            <div class="address-text">
                                <div class="address-name-row">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                                        <svg class="location-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <div style="display: flex; flex-direction: column;">
                                            <span class="address-name">{{ $userAddress?->label ?? '' }}</span>
                                            <div class="address-full"
                                                style="{{ is_null($userAddress) ? 'color: red;' : '' }}">
                                                {{ $userAddress?->address ?? 'Alamat belum diatur' }}
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/user/address" class="change-btn">Ubah</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-card payment-card">
                <div class="payment-methodss">
                    <div class="method-title">Metode Pembayaran</div>
                    <button id="openPaymentBtn" class="paymentOption-btn">
                        Pilih
                    </button>
                </div>

                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Ongkir</span>
                        <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Biaya Admin</span>
                        <span>Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                </div>
                <div id="addressInfo" data-address="{{ $userAddress ? 'present' : 'null' }}"></div>

                <div class="promo-section">
                    <input type="text" class="promo-input" placeholder="Masukkan Kode Promo">
                </div>
                <span class="warn-msg" id="warn-msg"> </span>

                <button class="confirm-btn">
                    Konfirmasi Pembayaran
                </button>
            </div>

        </div>

    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script src="{{ asset('js/checkout.js') }}" defer></script>
</body>
</html>
