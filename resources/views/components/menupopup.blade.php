<div id="menuPopup" class="menu-popup-overlay" style="display: none;">
    <div class="menu-popup-container">
        <div class="menu-popup-content">
            <button class="menu-popup-close" onclick="closeMenuPopup()">&times;</button>

            <div class="menu-popup-body">
                <!-- Food Image -->
                <div class="menu-popup-image">
                    <div id="popupImage" class="popup-image-container"></div>
                </div>

                <div class="menu-popup-details">
                    <h2 id="popupName" class="popup-menu-name">Nama Menu</h2>
                    <p class="popup-menu-category" id="popupMenuCategory">Menu Item</p>
                    <p id="popupPrice" class="popup-menu-price">Rp 0</p>
                    <p id="popupStock" class="popup-menu-stock">Stock: 0</p>

                    <p id="popupDescription" class="popup-menu-description">
                        Menu description will appear here...
                    </p>
                </div>
            </div>

            @php
                $isGuest = !auth()->check();
            @endphp
            <!-- Bottom Section -->
            <div class="popup-bottom-section">
                <div class="popup-quantity-controls">
                    <span class="quantity-label">Jumlah</span>
                    <div class="quantity-buttons">
                        <button id="decrementBtn" class="quantity-btn decrement" onclick="decrementQuantity()">
                            <span>-</span>
                        </button>
                        <span id="quantityDisplay" class="quantity-display">1</span>
                        <button id="incrementBtn" class="quantity-btn increment" onclick="incrementQuantity()">
                            <span>+</span>
                        </button>
                    </div>
                    @if (!$isGuest)
                        <form method="POST" action="{{ route('favorite.store') }}" style="margin-left: auto;">
                            @csrf
                            <input type="hidden" id="popupMenuId" name="menu_id" value="">
                            <button id="favoriteToggleBtn" class="favorite-btn" onclick="toggleFavorite()">
                                <svg id='heartIcon' xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#fe4a49" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.172 5.172a4.5 4.5 0 0 1 6.364 0L12 7.636l2.464-2.464a4.5 4.5 0 1 1 6.364 6.364L12 21 3.172 11.536a4.5 4.5 0 0 1 0-6.364z" />
                                </svg>
                            </button>

                        </form>
                    @endif
                </div>


                <div style="text-align: center; margin-top: 1rem;">
                    <button id="addToCartBtn"
                            class="add-to-cart-btn"
                            onclick="addToCart()"
                            {{ $isGuest ? 'disabled' : '' }}
                            style="
                                padding: 10px 20px;
                                font-size: 1rem;
                                background-color: {{ $isGuest ? '#ccc' : '#fe4a49' }};
                                color: white;
                                border: none;
                                cursor: {{ $isGuest ? 'not-allowed' : 'pointer' }};
                                opacity: {{ $isGuest ? '0.6' : '1' }};
                            ">
                        Tambahkan Pesanan
                    </button>

                    @if ($isGuest)
                        <p style="color: red; font-size: 0.65rem; margin-top: 0.5rem;">
                            Anda harus login terlebih dahulu
                        </p>
                    @endif
                </div>


            </div>
        </div>
    </div>
</div>
