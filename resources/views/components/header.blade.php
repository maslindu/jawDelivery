<header class="header">
    <h1 class="logo">JawDelivery</h1>

    @if (Request::is('dashboard'))
        <div class="search-container">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
            <input type="text" class="search-input" id='searchInput' placeholder="Search Menu">
        </div>
    @endif

    <div class="auth-buttons">
        @php
            $user = auth()->user();
            $homeUrl = '/dashboard';

            if ($user && $user->hasRole('admin')) {
                $homeUrl = '/admin';
            } elseif ($user && $user->hasRole('kurir')) {
                $homeUrl = '/driver';
            }
        @endphp
        @if (!Request::is('dashboard') && !Request::is('admin') && !Request::is('driver*'))
            <div style="display: flex; align-items: center; gap: 10px; font-weight:600">
                <a href="{{ url($homeUrl) }}" style="text-decoration: none; color: #333;">Home</a>
                <div style="width: 2px; height: 25px; background-color: #ccc;"></div>
            </div>
        @endif

        @guest
            <a href="{{ route('login') }}" class="btn btn-login">LOGIN</a>
            <a href="{{ route('register') }}" class="btn btn-register">REGISTER</a>
        @endguest

        @auth
            @php
                $user = auth()->user();
                $isAdmin = $user->hasRole('admin');
                $isDriver = $user->hasRole('kurir');
            @endphp

            @if (!Request::is('checkout') && !$isAdmin && !$isDriver)
                <a href="{{ route('checkout') }}" class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="22" height="22">
                        <path
                            d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                    </svg>
                </a>
            @endif

            <a class="user-info-link" id="profileButton">
                <div class="avatar" style="background-image: url('{{ Auth::user()->avatar_url }}');
                                    background-size: cover;
                                    background-position: center;"></div>
                <span>{{ Auth::user()->username }}</span>
            </a>

            <div class="dropdown" id="dropdown">
                <a href="{{ url('/profile') }}" style="text-decoration: none; color: inherit;">
                    <div class="user-info">
                        <div class="user-avatar" style="background-image: url('{{ Auth::user()->avatar_url }}');
                                            background-size: cover;
                                            background-position: center;"></div>
                        <div class="user-details">
                            <div class="user-name">
                                <span>{{ auth::user()->username }}</span>
                            </div>
                            <div class="user-email">
                                <span>{{ auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.orders') }}">
                        <div class="profile-option">Kelola Pesanan</div>
                    </a>
                    
                    <a href="{{ route('admin.manage-menu') }}">
                        <div class="profile-option">Kelola Menu & Kategori</div>
                    </a>
                    
                    <a href="{{ route('admin.manage-driver') }}">
                        <div class="profile-option">Kelola Driver</div>
                    </a>
                    
                    <a href="{{ route('admin.manage-users') }}">
                        <div class="profile-option">Kelola Pengguna</div>
                    </a>
                    
                    <a href="{{ route('admin.financial-reports') }}">
                        <div class="profile-option">Laporan</div>
                    </a>
                    
                @elseif(auth()->user()->hasRole('kurir'))
                    {{-- Driver/Kurir Menu Options --}}
                    <a href="{{ route('driver.profile') }}">
                        <div class="profile-option">
                            <svg width="16" height="16" style="margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Profil
                        </div>
                    </a>
                    
                    <a href="{{ route('driver.ready-orders') }}">
                        <div class="profile-option">
                            <svg width="16" height="16" style="margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Pesanan Siap Diantar
                        </div>
                    </a>
                    
                    <a href="{{ route('driver.processing-orders') }}">
                        <div class="profile-option">
                            <svg width="16" height="16" style="margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pesanan Sedang Diproses
                        </div>
                    </a>
                    
                    <a href="{{ route('driver.delivery-history') }}">
                        <div class="profile-option">
                            <svg width="16" height="16" style="margin-right: 8px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            History Pengantaran
                        </div>
                    </a>
                    
                @else
                    @if(Route::has('user.address'))
                        <a href="{{ route('user.address') }}">
                            <div class="profile-option">Daftar Alamat</div>
                        </a>
                    @endif

                    @if(Route::has('user.history'))
                        <a href="{{ route('user.history') }}">
                            <div class="profile-option">Daftar Transaksi</div>
                        </a>
                    @endif

                    @if(Route::has('favorite.index'))
                        <a href="{{ route('favorite.index') }}">
                            <div class="profile-option">Menu Favorit</div>
                        </a>
                    @endif
                @endif

                <div class="logout-button" id=logoutButton>LOGOUT</div>
            </div>
        @endauth
    </div>
    
    <div id="logoutPopup" class="modal" style="display:none; position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%); background: white; border: 1px solid #ccc; padding: 20px; z-index: 1000;">
        <div class="modal-title">Logout?</div>
        <div class="button-container">
            <button class="logout-btn" id="confirmLogout">LOGOUT</button>
            <button class="cancel-btn" id="cancelLogout">BATAL</button>
        </div>
        <form id="logoutForm" action="{{ route('api.auth.logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
    
    <div id="popupOverlay" style="display:none; position: fixed; top: 0; left: 0;
    width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>
</header>
