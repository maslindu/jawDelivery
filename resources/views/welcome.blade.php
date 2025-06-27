<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Pesan Makanan Favorit Anda</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
    <!-- Header Navigation -->
    <header class="header">
        <nav class="nav-container">
            <a href="#" class="logo">JawDelivery</a>
            <div class="nav-buttons">
                <span class="nav-tagline">✨ Nikmati Pengalaman Pesan Makanan Terbaik</span>
            </div>
        </nav>
    </header>

    <!-- Floating Food Icons -->
    <div class="floating-element floating-1">
        <div class="food-icon icon-1">🍕</div>
    </div>
    <div class="floating-element floating-2">
        <div class="food-icon icon-2">🍔</div>
    </div>
    <div class="floating-element floating-3">
        <div class="food-icon icon-3">🍜</div>
    </div>
    <div class="floating-element floating-4">
        <div class="food-icon icon-4">🥘</div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="hero-container fade-in">
            <h1 class="hero-title">Selamat Datang di JawDelivery</h1>
            <p class="hero-subtitle">Platform Pesan Antar Makanan Terpercaya</p>
            
            <p class="hero-description">
                JawDelivery adalah web pemesan makanan siap antar yang menghubungkan Anda dengan restoran JAW favorit. 
                Nikmati pengalaman pesan makanan yang mudah, cepat, dan terpercaya dengan pilihan menu lezat 
                yang siap diantar langsung ke pintu rumah Anda. Dengan sistem pembayaran yang aman dan 
                pengiriman yang cepat, kami pastikan makanan sampai dalam kondisi hangat dan fresh!
            </p>

            <div class="cta-buttons">
                <a href="/dashboard" class="cta-btn btn-primary">
                    🚀 Mulai Pesan Sekarang
                </a>
                <a href="/login" class="cta-btn btn-secondary">
                    🔑 Masuk
                </a>
                <a href="/register" class="cta-btn btn-secondary">
                    📱 Daftar Gratis
                </a>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">Pelanggan Puas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Menu Pilihan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Layanan</span>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section slide-up">
            <div class="feature-card feature-1">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Pengiriman Cepat</h3>
                <p class="feature-description">Makanan sampai dalam 30 menit atau gratis! Dengan jaringan kurir terpercaya di seluruh kota.</p>
            </div>
            
            <div class="feature-card feature-2">
                <div class="feature-icon">🛡️</div>
                <h3 class="feature-title">Pembayaran Aman</h3>
                <p class="feature-description">Berbagai metode pembayaran aman mulai dari COD, transfer bank, hingga e-wallet.</p>
            </div>
            
            <div class="feature-card feature-3">
                <div class="feature-icon">⭐</div>
                <h3 class="feature-title">Kualitas Terjamin</h3>
                <p class="feature-description">Menu berkualitas dari restoran JAW dengan cita rasa autentik yang selalu fresh.</p>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = 'none';
            }
        });

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        const animateCounter = (element, target) => {
            let current = 0;
            const increment = target / 60;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (element.textContent.includes('+') ? '+' : '');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (element.textContent.includes('+') ? '+' : '');
                }
            }, 16);
        };

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const numbers = entry.target.querySelectorAll('.stat-number');
                    numbers.forEach(num => {
                        const text = num.textContent;
                        if (text.includes('1000')) animateCounter(num, 1000);
                        else if (text.includes('50')) animateCounter(num, 50);
                        else if (text.includes('24/7')) num.textContent = '24/7';
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statsObserver.observe(document.querySelector('.stats-section'));
    </script>
</body>
</html>