<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <title>JawDelivery</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menupopup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
</head>
<body>
    @include('components.header')
    @include('components.menupopup')
    @include('components.notification')

    <main class="main-content">

        <section class="menu-section">
            <div style="height:25px"></div>
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
                            <svg class="checkmark" style="display: none; width: 1em; height: 1em; margin-right: 6px" fill="none" stroke="black" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
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
                        <div class="menu-item" id='menuItem'
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-price="{{ $item->price }}"
                            data-stock="{{ $item->stock }}"
                            data-description="{{ $item->description }}"
                            data-categories="{{ $item->categories->pluck('name')->implode(', ') }}"
                            data-image-url="{{ $item->image_url }}"
                            data-is-fav="{{$item->is_fav}}">

                            @if (!empty($item->image_link))
                                <div class="menu-item-image"
                                    style="background-image: url('{{ $item->image_url }}');
                                    background-size: cover;
                                    background-position: center;">
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


</body>
<script src="{{ asset('js/header.js') }}" defer></script>
<script src="{{ asset('js/menupopup.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('#category-filters .filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');
    const searchBox = document.getElementById('searchInput');

    const activeCategories = new Set();

    function filterMenuItems() {
        const query = searchBox?.value.toLowerCase().trim() || '';

        menuItems.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const itemCategories = item.dataset.categories.split(',').map(c => c.trim());

            const matchesSearch = name.includes(query);
            const matchesCategory = activeCategories.size === 0 || [...activeCategories].some(cat => itemCategories.includes(cat));

            item.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
        });
    }

    // Handle category filters
    filterButtons.forEach(button => {
        const categoryName = button.textContent.trim();

        button.addEventListener('click', () => {
            const isActive = activeCategories.has(categoryName);

            if (isActive) {
                activeCategories.delete(categoryName);
                button.classList.remove('active-filter');
                button.querySelector('.checkmark').style.display = 'none';
            } else {
                activeCategories.add(categoryName);
                button.classList.add('active-filter');
                button.querySelector('.checkmark').style.display = 'inline';
            }

            filterMenuItems();
        });
    });

    // Handle search input
    if (searchBox) {
        searchBox.addEventListener('input', filterMenuItems);
    }
});
</script>



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

        setTimeout(updateScrollButtons, 150);
    }

    window.addEventListener('load', updateScrollButtons);
    scrollContainer.addEventListener('scroll', updateScrollButtons);


</script>
</html>
