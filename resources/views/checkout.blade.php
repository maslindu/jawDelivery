<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Checkout Pesanan</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>

<body>
    @include('components.header')

    <main class="main-content">
        <!-- Order Section -->
        <div class="order-section">
            <div class="section-title-wrapper">
                <h2 class="section-title">Pesanan</h2>
            </div>
            

            <div class="product-item">
                <div class="product-image"></div>
                <div class="product-info">
                    <div class="product-name">Nama Produk</div>
                    <div class="product-description">Description</div>
                </div>
                <div class="product-price">Rp 10.000</div>
                <div class="quantity-controls">
                    <button class="quantity-btn">-</button>
                    <span class="quantity-display">10</span>
                    <button class="quantity-btn">+</button>
                </div>
                <button class="delete-btn">🗑</button>
            </div>

            <div class="product-item">
                <div class="product-image"></div>
                <div class="product-info">
                    <div class="product-name">Nama Produk</div>
                    <div class="product-description">Description</div>
                </div>
                <div class="product-price">Rp 10.000</div>
                <div class="quantity-controls">
                    <button class="quantity-btn">-</button>
                    <span class="quantity-display">10</span>
                    <button class="quantity-btn">+</button>
                </div>
                <button class="delete-btn">🗑</button>
            </div>

            <div class="product-item">
                <div class="product-image"></div>
                <div class="product-info">
                    <div class="product-name">Nama Produk</div>
                    <div class="product-description">Description</div>
                </div>
                <div class="product-price">Rp 10.000</div>
                <div class="quantity-controls">
                    <button class="quantity-btn">-</button>
                    <span class="quantity-display">10</span>
                    <button class="quantity-btn">+</button>
                </div>
                <button class="delete-btn">🗑</button>
            </div>

            <div class="order-total">
                <span class="total-text">Total Pesanan : Rp 15.000</span>
                <button class="notes-btn">Catatan</button>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <!-- Address Card -->
            <div class="summary-card">
                <div class="address-section">
                    <div class="address-info">
                        <div class="address-title">Alamat Pengiriman</div>
                        <div class="address-details">
                            <!--<div class="location-icon">📍</div>-->
                            <!--<i class="material-icons">place</i>-->
                            <svg class="location-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="address-text">
                                <div class="address-name-row">
                                    <span class="address-name">Rumah User</span>
                                    <button class="change-btn">Ubah</button>
                                </div>
                                <div class="address-full">Jl.Surakarta Timur No.999</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="payment-method">
                    <div class="method-title">Metode Pembayaran</div>
                    <div class="payment-selector">
                        <button class="paymentOption-btn">Transfer</button>
                        <div class="payment-dropdown">
                            <div class="payment-option" data-value="tunai">Tunai</div>
                            <div class="payment-option" data-value="transfer">Transfer</div>
                        </div>
                    </div>
                </div>

                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>Rp 15.000</span>
                    </div>
                    <div class="price-row">
                        <span>Ongkir</span>
                        <span>Rp 10.000</span>
                    </div>
                    <div class="price-row">
                        <span>Biaya Admin</span>
                        <span>Rp 1.000</span>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>Rp 16.000</span>
                    </div>
                </div>

                <div class="promo-section">
                    <input type="text" class="promo-input" placeholder="Masukkan Kode Promo">
                </div>

                <button class="confirm-btn">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script>
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const isIncrement = this.textContent === '+';
                const display = isIncrement ?
                    this.previousElementSibling :
                    this.nextElementSibling;

                let currentValue = parseInt(display.textContent);

                if (isIncrement) {
                    currentValue++;
                } else if (currentValue > 1) {
                    currentValue--;
                }

                display.textContent = currentValue;
            });
        });

        // Delete item
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Hapus item dari pesanan?')) {
                    this.closest('.product-item').remove();
                }
            });
        });

        // Notes button
        document.querySelector('.notes-btn').addEventListener('click', function() {
            const note = prompt('Masukkan catatan untuk pesanan:');
            if (note) {
                console.log('Catatan:', note);
            }
        });

        // Confirm payment
        document.querySelector('.confirm-btn').addEventListener('click', function() {
            alert('Mengarahkan ke halaman pembayaran...');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.paymentOption-btn');
            const dropdown = document.querySelector('.payment-dropdown');

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selected = this.getAttribute('data-value');
                    btn.textContent = this.textContent; // Update tombol
                    dropdown.style.display = 'none';
                });
            });

            document.addEventListener('click', function() {
                dropdown.style.display = 'none';
            });

            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>


</body>

</html>