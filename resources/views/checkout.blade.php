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
</head>
<body>
    @include('components.header')

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
                    </div>
                    <div class="product-price">Rp {{ number_format($item->menu->price, 0, ',', '.') }}</div>
                    <div class="quantity-controls">
                        <button class="quantity-btn decrement" data-id="{{ $item->id }}">-</button>
                        <span class="quantity-display">{{ $item->quantity }}</span>
                        <button class="quantity-btn increment" data-id="{{ $item->id }}">+</button>
                    </div>
                    <button class="delete-btn" data-id="{{ $item->id }}">🗑</button>
                </div>
            @endforeach
            </div>


            <div class="order-total">
                <span class="total-text">Total Pesanan :  Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                <button class="notes-btn">Catatan</button>
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
                <div class="payment-method">
                    <div class="method-title">Metode Pembayaran</div>
                    <button class="paymentOption-btn">Transfer</button>
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

                <div class="promo-section">
                    <input type="text" class="promo-input" placeholder="Masukkan Kode Promo">
                </div>

                <button
                    class="confirm-btn {{ is_null($userAddress) ? 'disabled' : '' }}"
                    {{ is_null($userAddress) ? 'disabled' : '' }}>
                    Konfirmasi Pembayaran
                </button>
            </div>

        </div>

    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script>
document.querySelectorAll('.quantity-btn').forEach(button => {
    button.addEventListener('click', async (e) => {
        e.preventDefault();

        const cartId = e.currentTarget.dataset.id;
        if (!cartId) {
            console.error('No cart ID provided');
            return;
        }

        const isIncrement = button.classList.contains('increment');
        const display = button.parentElement.querySelector('.quantity-display');
        let quantity = parseInt(display.textContent);
        quantity = isIncrement ? quantity + 1 : Math.max(1, quantity - 1);

        try {
            const response = await fetch(`/cart/${cartId}/quantity`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity })
            });

            if (!response.ok) throw new Error("Server error");

            const data = await response.json();
            display.textContent = data.quantity;

            document.querySelector('.total-text').textContent = `Total Pesanan : Rp ${data.subtotal.toLocaleString('id-ID')}`;
            document.querySelector('.price-breakdown .price-row:nth-child(1) span:nth-child(2)').textContent = `Rp ${data.subtotal.toLocaleString('id-ID')}`;
            document.querySelector('.price-breakdown .price-row:nth-child(2) span:nth-child(2)').textContent = `Rp ${data.shipping.toLocaleString('id-ID')}`;
            document.querySelector('.price-breakdown .price-row:nth-child(3) span:nth-child(2)').textContent = `Rp ${data.adminFee.toLocaleString('id-ID')}`;
            document.querySelector('.total-row span:nth-child(2)').textContent = `Rp ${data.total.toLocaleString('id-ID')}`;
        } catch (error) {
            console.error("Failed to update quantity:", error);
        }
    });
});


document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async (e) => {
        e.preventDefault()
        const cartId = e.currentTarget.dataset.id;
        const productItem = e.currentTarget.closest('.product-item');


        if (!cartId) {
            console.error('Cart ID is missing');
            return;
        }

        fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Server error');
            return response.json();
        })
        .then(data => {
            console.log("nice")
            console.log('Deleted successfully', data);
            if (productItem) {
                productItem.remove();
            }

        })
        .catch(error => {
            console.error('Failed to delete:', error);
        });
    });
});



        function updateTotals(data) {
            document.querySelector('.subtotal-amount').textContent = `Rp ${formatRupiah(data.subtotal)}`;
            document.querySelector('.shipping-amount').textContent = `Rp ${formatRupiah(data.shipping)}`;
            document.querySelector('.adminfee-amount').textContent = `Rp ${formatRupiah(data.adminFee)}`;
            document.querySelector('.total-amount').textContent = `Rp ${formatRupiah(data.total)}`;
            document.querySelector('.total-text').textContent = `Total Pesanan : Rp ${formatRupiah(data.subtotal)}`;
        }

        function formatRupiah(number) {
            return number.toLocaleString('id-ID');
        }
    </script>

    <script>
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
</body>
</html>
