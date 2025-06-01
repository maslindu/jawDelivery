<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <title>JawDelivery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            width="50" height="50"
            min-height: 100vh;
        }

        .header {
            background-color: #f4f4f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 32px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
        }

        .search-container {
            position: relative;
            flex: 1;
            max-width: 512px;
            margin: 0 32px;
        }

        .search-input {
            width: 100%;
            padding: 12px 12px 12px 48px;
            border: 2px solid #000000;
            border-radius: 50px;
            background-color: #ffffff;
            color: #828282;
            font-size: 16px;
            outline: none;
        }

        .search-input::placeholder {
            color: #828282;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #828282;
        }

        .auth-buttons {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 50px;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-login {
            background-color: #fe4a49;
            color: #ffffff;
        }

        .btn-register {
            background-color: #2ab7ca;
            color: #ffffff;
        }

        .main-content {
            padding: 32px;
        }

        .featured-section {
            max-width: 1024px;
            margin: 0 auto 48px auto;
        }

        .featured-menu {
            background-color: #2ab7ca;
            border: 4px solid #000000;
            border-radius: 24px;
            height: 192px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .featured-title {
            font-size: 24px;
            font-weight: 600;
            color: #000000;
        }

        .menu-section {
            max-width: 1024px;
            margin: 0 auto;
        }

        .menu-title {
            font-size: 20px;
            font-weight: bold;
            color: #000000;
            text-align: center;
            margin-bottom: 32px;
        }



        .menu-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 32px;
        }

        .menu-item {
            text-align: center;
        }

        .menu-item-image {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 128px;
            height: 128px;
            background-color: #d9d9d9;
            border: 2px solid #000000;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .menu-item-name {
            color: #000000;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 16px;
                padding: 16px;
            }

            .search-container {
                margin: 0;
                max-width: 100%;
            }

            .auth-buttons {
                width: 100%;
                justify-content: center;
            }

            .category-filters {
                flex-wrap: wrap;
                gap: 12px;
            }

            .menu-items {
                flex-direction: column;
                align-items: center;
                gap: 24px;
            }
        }
        .icon-button {
            width: 40px;
            height: 40px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 2px solid black;
            border-radius: 50%;
            font-size: 10px;
            text-decoration: none;
        }

        .user-info-link {
            display: inline-flex;
            align-items: center;
            border: 2px solid black;
            border-radius: 13px;
            padding: 5px 10px;
            text-decoration: none;
            color: inherit;
            background: white;
        }

        .avatar {
            width: 24px;
            height: 24px;
            background: #ccc;
            border-radius: 50%;
            margin-right: 12px;
        }

        .category-filters {
            display: flex;
            gap: 24px;
            margin-bottom: 48px;
            scrollbar-width: none; 
            scroll-behavior: smooth;
            overflow-x: auto;
        }

        .category-filters::-webkit-scrollbar {
            display: none; 
        }

        .filter-btn {
            white-space: nowrap;
            padding: 8px 24px;
            border: 2px solid;
            border-radius: 50px;
            background-color: transparent;
            color: #000000;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            width: max-content;
        }
        
        .category-scroll-wrapper {
            display: flex;
            justify-content: center;
            max-width: 100%;
            cursor: grab;
            user-select: none;
            
        }
        .category-scroll-wrapper:active {
            cursor: grabbing;
        }

        .category-scroll-wrapper:active.category-filters {
            cursor: grabbing;
        }
        .category-filters {
            display: flex;
            gap: 16px;
            padding: 8px 0;
        }

        .filter-btn-red {
            border-color: #fe4a49;
        }

        .filter-btn-red:hover {
            background-color: rgba(254, 74, 73, 0.1);
        }

        .filter-btn-teal {
            border-color: #2ab7ca;
        }

        .filter-btn-teal:hover {
            background-color: rgba(42, 183, 202, 0.1);
        }

        .filter-btn-yellow {
            border-color: #fed766;
        }

        .filter-btn-yellow:hover {
            background-color: rgba(254, 215, 102, 0.1);
        }
        .scroll-btn-container {
            padding-bottom: 48px; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 40px;
            min-height: 40px;
        }
        .scroll-btn {
            cursor: pointer;
            background: transparent;
            border: 0px;
            color: black;
        }

        .scroll-btn.left {
            left: 0;
        }

        .scroll-btn.right {
            right: 0;
        }
        .dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
            z-index: 100;
            display: none;
        }

        .dropdown.active {
            display: block;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #eee;
            margin-right: 15px;
        }

        .user-details {
            flex-grow: 1;
        }

        .user-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .user-email {
            font-size: 14px;
            color: #666;
        }

        .logout-button {
            padding: 15px;
            text-align: center;
            color: #ff3b30;
            font-weight: bold;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #f9f9f9;
        }
        .profile-option {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .profile-option:hover {
            background-color: #f9f9f9;
        }


    </style>
</head>
<body>


    <!-- Header -->
    <header class="header">
        <h1 class="logo">JawDelivery</h1>

        <div class="search-container">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
            </svg>
            <input type="text" class="search-input" placeholder="Search Menu">
        </div>
        <div class="auth-buttons">
            @guest
                <a href="{{ route('login') }}" class="btn btn-login">LOGIN</a>
                <a href="{{ route('register') }}" class="btn btn-register">REGISTER</a>
            @endguest
        
            @auth
                <a href="" class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="22" height="22"><path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>
                </a>
            
                <a class="user-info-link" id="profileButton">
                    <div class="avatar"></div>
                    <span>{{ Auth::user()->username }}</span>
                </a>
                <div class="dropdown" id="dropdown">
                    <div class="user-info">
                        <div class="user-avatar"></div>
                        <div class="user-details">
                            <div class="user-name">
                                <span>{{ Auth::user()->username }}</span>
                            </div>
                            <div class="user-email">
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-option">Daftar Alamat</div>
                    <div class="profile-option">Daftar Transaksi</div>
                    <div class="profile-option">Menu Favorit</div>
                    <div class="profile-option">Promo yang Dimiliki</div>
                    <div class="logout-button" id="logoutButton">LOGOUT</div>
                </div>
            @endauth
        </div>
    </header>

    <main class="main-content">
        <section class="featured-section">
            <div class="featured-menu">
                <h2 class="featured-title">Featured Menu</h2>
            </div>
        </section>

        <section class="menu-section">
            <h2 class="menu-title">OUR MENU</h2>

            <div class="category-scroll-wrapper">
                <div class="scroll-btn-container">
                    <button class="scroll-btn left" id="scroll-left" onclick="scrollCategories(-1)" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
                    </button>
                </div>
                <div class="category-filters" id="category-filters">
                    @foreach ($categories as $index => $category)
                        @php
                            $colors = ['red', 'teal', 'yellow'];
                            $color = $colors[$index % count($colors)];
                        @endphp
                        <button class="filter-btn filter-btn-{{ $color }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
                <div class="scroll-btn-container">
                    <button class="scroll-btn right" id="scroll-right" onclick="scrollCategories(1)" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#00000"><path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"/></svg>
                    </button>
                </div>
            
            </div>
            
            <div class="menu-items">
                @if ($menuItems->isEmpty())
                <p style="
                color: #d5d5da;
                font-size: 2rem;
                text-align: center;
                margin: 2rem 0;
                font-weight: bold;
                ">    
                    Tidak Ada Menu yang dapat ditampilkan
                </p>
                @else
                    @foreach ($menuItems as $item)
                        <div class="menu-item">
                            @if (!empty($item->image))
                                <div 
                                    class="menu-item-image"
                                    style="background-image: url('data:{{ $item->image_type }};base64,{{ base64_encode($item->image) }}');">
                                </div>
                            @else
                                <div class="menu-item-image no-image-placeholder">
                                    <span>No Image</span>
                                </div>
                            @endif
                            <p class="menu-item-name">{{ $item->name }}</p>
                        </div>
                    @endforeach
            
                @endif
            </div>
        </section>
    </main>
    <div id="logoutPopup" style="display:none; position: fixed; top: 50%; left: 50%; 
        transform: translate(-50%, -50%); background: white; border: 1px solid #ccc; padding: 20px; z-index: 1000;">
        <p>Are you sure you want to logout?</p>
        <button id="confirmLogout">Yes, Logout</button>
        <button id="cancelLogout">Cancel</button>
        <form id="logoutForm" action="{{ route('api.auth.logout') }}" method="POST" style="display:none;">
            @csrf
        </form>

    </div>
    <div id="popupOverlay" style="display:none; position: fixed; top: 0; left: 0; 
        width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>
    

</body>
<script>
    const scrollWrapper = document.querySelector('.category-scroll-wrapper');
    const scrollTarget = document.getElementById('category-filters'); 

    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false;

    scrollTarget.addEventListener('mousedown', (e) => {
        isDown = true;
        isDragging = false;
        startX = e.pageX - scrollTarget.offsetLeft;
        scrollLeft = scrollTarget.scrollLeft;
        scrollWrapper.classList.add('active');
    });

    scrollTarget.addEventListener('mouseleave', () => {
        isDown = false;
        scrollWrapper.classList.remove('active');
    });

    scrollTarget.addEventListener('mouseup', () => {
        isDown = false;
        scrollWrapper.classList.remove('active');
    });

    scrollTarget.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollTarget.offsetLeft;
        const walk = (x - startX) * 2;

        if (Math.abs(walk) > 5) {
            isDragging = true;
        }

        scrollTarget.scrollLeft = scrollLeft - walk;
    });

    scrollTarget.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (isDragging) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
    });
    
    const scrollContainer = document.getElementById('category-filters');
    const scrollLeftBtn = document.getElementById('scroll-left');
    const scrollRightBtn = document.getElementById('scroll-right');

    function updateScrollButtons() {
        scrollLeftBtn.style.display = scrollContainer.scrollLeft > 0 ? 'block' : 'none';
        scrollRightBtn.style.display = (scrollContainer.scrollLeft + scrollContainer.offsetWidth) < scrollContainer.scrollWidth ? 'block' : 'none';
    }

    function scrollCategories(direction) {
        const scrollAmount = 150; 
        scrollContainer.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });

        setTimeout(updateScrollButtons, 150); // wait for smooth scroll
    }

    window.addEventListener('load', updateScrollButtons);
    scrollContainer.addEventListener('scroll', updateScrollButtons);
    
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const dropdown = document.getElementById('dropdown');

        profileButton.addEventListener('click', function() {
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
    const logoutButton = document.getElementById('logoutButton');
    const logoutPopup = document.getElementById('logoutPopup');
    const popupOverlay = document.getElementById('popupOverlay');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');

    logoutButton.addEventListener('click', function() {
        logoutPopup.style.display = 'block';
        popupOverlay.style.display = 'block';
    });

    cancelLogout.addEventListener('click', function() {
        logoutPopup.style.display = 'none';
        popupOverlay.style.display = 'none';
    });

    confirmLogout.addEventListener('click', function() {
        document.getElementById('logoutForm').submit();
    });


</script>
</html>