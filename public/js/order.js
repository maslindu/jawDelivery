// Order state configurations
const orderStates = {
    pending: {
        title: "Tunggu Pembayaran Dikonfirmasi",
        subtitle: "Kami sedang memverifikasi pembayaran Anda. Mohon tunggu beberapa saat.",
        icon: `
            <div class="clock-face">
                <div class="clock-hand hour-hand"></div>
                <div class="clock-hand minute-hand"></div>
                <div class="clock-center"></div>
            </div>
        `,
        statusText: "Menunggu Konfirmasi Pembayaran",
        statusDot: "pending",
        footerNote: "Pembayaran akan dikonfirmasi dalam 1x24 jam. Jika ada kendala, hubungi customer service kami."
    },
    cancelled: {
        title: "Pesanan Dibatalkan",
        subtitle: "Pesanan Anda telah dibatalkan. Dana akan dikembalikan dalam 1-3 hari kerja.",
        icon: `<div class="clock-face cancelled-icon">❌</div>`,
        statusText: "Pesanan Dibatalkan",
        statusDot: "cancelled",
        footerNote: "Jika Anda memiliki pertanyaan tentang pembatalan ini, silakan hubungi customer service kami."
    },
    processing: {
        title: "Pesanan Sedang Dimasak",
        subtitle: "Chef kami sedang menyiapkan pesanan Anda dengan penuh perhatian.",
        icon: `<div class="clock-face cooking-icon">👨‍🍳</div>`,
        statusText: "Sedang Dimasak",
        statusDot: "cooking",
        footerNote: "Estimasi waktu memasak: 15-30 menit. Kami akan segera mengirimkan pesanan Anda."
    },
    shipped: {
        title: "Pesanan Sedang Diantar",
        subtitle: "Kurir kami sedang dalam perjalanan menuju lokasi Anda.",
        icon: `<div class="clock-face delivery-icon">🏍️</div>`,
        statusText: "Sedang Diantar",
        statusDot: "delivery",
        footerNote: "Estimasi waktu tiba: 10-20 menit. Pastikan Anda berada di lokasi pengiriman."
    },
    delivered: {
        title: "Pesanan Selesai",
        subtitle: "Terima kasih! Pesanan Anda telah berhasil diantar. Selamat menikmati!",
        icon: `<div class="clock-face completed-icon">✅</div>`,
        statusText: "Pesanan Selesai",
        statusDot: "completed",
        footerNote: "Jangan lupa berikan rating dan review untuk membantu kami memberikan layanan yang lebih baik."
    }
};

function changeOrderState(state) {
    const config = orderStates[state];
    if (!config) return;

    document.getElementById('statusTitle').textContent = config.title;
    document.getElementById('statusSubtitle').textContent = config.subtitle;

    document.getElementById('statusIcon').innerHTML = config.icon;

    document.getElementById('statusText').textContent = config.statusText;
    document.getElementById('footerNote').textContent = config.footerNote;

    const statusDot = document.getElementById('statusDot');
    statusDot.className = `status-dot ${config.statusDot}`;

    const statusSection = document.querySelector('.status-section');
    statusSection.style.transform = 'scale(0.95)';
    statusSection.style.opacity = '0.7';

    setTimeout(() => {
        statusSection.style.transform = 'scale(1)';
        statusSection.style.opacity = '1';
    }, 150);
}

document.addEventListener('DOMContentLoaded', function () {
    const mainElement = document.querySelector('.main-content');
    if (!mainElement) return;

    const status = mainElement.getAttribute('data-order-status');
    if (status) {
        changeOrderState(status); // Automatically call based on backend status
    }
});
