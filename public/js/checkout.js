const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
let selectedPaymentMethod = null;
let addressIsPresent = false;

document.addEventListener('DOMContentLoaded', init);

function init() {
    const addressInfo = document.getElementById('addressInfo');
    addressIsPresent = addressInfo?.dataset.address === 'present';

    const confirmBtn = document.querySelector('.confirm-btn');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('disabled');
    }
    
    updateConfirmButtonState();
    bindQuantityButtons();
    bindDeleteButtons();
    bindConfirmButton();
    bindModalEvents();

    document.getElementById('openPaymentBtn')?.addEventListener('click', openPaymentModal);
}

function openPaymentModal() {
    document.getElementById('paymentModal')?.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function bindQuantityButtons() {
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', (e) => handleQuantityChange(e, button));
    });
}

async function handleQuantityChange(e, button) {
    e.preventDefault();
    
    const cartId = button.dataset.id;
    const isIncrement = button.classList.contains('increment');
    const productItem = button.closest('.product-item');
    const display = productItem.querySelector('.quantity-display');
    const quantity = parseInt(display.textContent);
    const stock = parseInt(button.dataset.stock);

    if (!cartId) {
        console.error('No cart ID provided');
        return;
    }

    // Validasi sebelum melakukan perubahan
    if (isIncrement) {
        if (quantity >= stock) {
            showNotification('Stok tidak mencukupi!', 'error');
            return;
        }
        await updateCartQuantity(cartId, quantity + 1, display, productItem);
    } else {
        if (quantity <= 1) {
            // Jika quantity 1 dan dikurangi, hapus item
            if (confirm('Hapus item dari keranjang?')) {
                await deleteCartItem(cartId, productItem);
            }
        } else {
            await updateCartQuantity(cartId, quantity - 1, display, productItem);
        }
    }
}

async function updateCartQuantity(cartId, newQuantity, display, productItem) {
    // Disable buttons sementara untuk mencegah multiple clicks
    const buttons = productItem.querySelectorAll('.quantity-btn');
    buttons.forEach(btn => btn.disabled = true);

    try {
        const response = await fetch(`/user/cart/${cartId}/quantity`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQuantity })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || "Server error");
        }

        const data = await response.json();
        
        // Update UI
        display.textContent = data.quantity;
        updatePriceSummary(data);
        updateConfirmButtonState();
        
        showNotification('Keranjang diperbarui', 'success');

    } catch (error) {
        console.error("Failed to update quantity:", error);
        showNotification(error.message || 'Gagal memperbarui keranjang', 'error');
    } finally {
        // Re-enable buttons
        buttons.forEach(btn => btn.disabled = false);
    }
}

function bindDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', deleteCartItemHandler);
    });
}

function deleteCartItemHandler(e) {
    e.preventDefault();
    
    const cartId = e.currentTarget.dataset.id;
    const productItem = e.currentTarget.closest('.product-item');
    
    if (!cartId) {
        console.error('No cart ID found');
        return;
    }
    
    if (confirm('Hapus item dari keranjang?')) {
        deleteCartItem(cartId, productItem);
    }
}

async function deleteCartItem(cartId, productItem) {
    // Disable delete button sementara
    const deleteBtn = productItem.querySelector('.delete-btn');
    if (deleteBtn) deleteBtn.disabled = true;

    try {
        const response = await fetch(`/user/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to delete item');
        }

        const data = await response.json();
        
        // Remove item from DOM dengan animasi
        productItem.style.transition = 'opacity 0.3s ease';
        productItem.style.opacity = '0';
        
        setTimeout(() => {
            productItem.remove();
            updatePriceSummary(data);
            updateConfirmButtonState();
            
            // Check jika keranjang kosong
            const remainingItems = document.querySelectorAll('.product-item');
            if (remainingItems.length === 0) {
                showEmptyCartMessage();
            }
        }, 300);
        
        showNotification('Item dihapus dari keranjang', 'success');

    } catch (error) {
        console.error('Failed to delete:', error);
        showNotification(error.message || 'Gagal menghapus item', 'error');
        
        // Re-enable delete button jika error
        if (deleteBtn) deleteBtn.disabled = false;
    }
}

function updatePriceSummary(data) {
    const totalText = document.querySelector('.total-text');
    if (totalText) {
        totalText.textContent = `Total Pesanan : Rp ${formatRupiah(data.subtotal)}`;
    }
    
    const priceRows = document.querySelectorAll('.price-breakdown .price-row span:nth-child(2)');
    if (priceRows.length >= 3) {
        priceRows[0].textContent = `Rp ${formatRupiah(data.subtotal)}`;
        priceRows[1].textContent = `Rp ${formatRupiah(data.shipping)}`;
        priceRows[2].textContent = `Rp ${formatRupiah(data.adminFee)}`;
    }
    
    const totalRow = document.querySelector('.total-row span:nth-child(2)');
    if (totalRow) {
        totalRow.textContent = `Rp ${formatRupiah(data.total)}`;
    }
}

function formatRupiah(number) {
    return number.toLocaleString('id-ID');
}

function showEmptyCartMessage() {
    const orderSection = document.querySelector('.order-section');
    if (orderSection) {
        orderSection.innerHTML = `
            <div class="empty-cart-message" style="text-align: center; padding: 2rem;">
                <h3>Keranjang Kosong</h3>
                <p>Silakan tambahkan item ke keranjang terlebih dahulu</p>
                <a href="/dashboard" class="btn-primary" style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #ff6b6b; color: white; text-decoration: none; border-radius: 5px;">
                    Kembali Belanja
                </a>
            </div>
        `;
    }
}

function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        color: white;
        z-index: 10000;
        transition: all 0.3s ease;
        font-weight: 500;
        ${type === 'success' ? 'background: #28a745;' : ''}
        ${type === 'error' ? 'background: #dc3545;' : ''}
        ${type === 'info' ? 'background: #17a2b8;' : ''}
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function bindConfirmButton() {
    const confirmBtn = document.querySelector('.confirm-btn');
    if (!confirmBtn) return;

    confirmBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        
        if (confirmBtn.disabled) return;
        
        const notes = document.querySelector('.notes-input')?.value.trim() || '';
        
        // Validate before submitting
        if (!selectedPaymentMethod) {
            showNotification('Pilih metode pembayaran terlebih dahulu', 'error');
            return;
        }
        
        if (!addressIsPresent) {
            showNotification('Alamat pengiriman belum diatur', 'error');
            return;
        }
        
        // Disable button to prevent double submission
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Memproses...';

        try {
            const response = await fetch('/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    payment_method: selectedPaymentMethod,
                    notes: notes
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal membuat pesanan');
            }

            const result = await response.json();
            showNotification('Pesanan berhasil dibuat!', 'success');
            
            // Redirect ke halaman order detail
            setTimeout(() => {
                window.location.replace(`/order/${result.order_id}`);
            }, 1000);

        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat membuat pesanan', 'error');
            
            // Re-enable button
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Konfirmasi Pembayaran';
        }
    });
}

function bindModalEvents() {
    const modal = document.getElementById('paymentModal');
    const pilihButton = document.getElementById('pilihButton');

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closePaymentModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePaymentModal();
    });

    if (pilihButton) {
        pilihButton.addEventListener('click', confirmPaymentMethod);
    }

    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', () => togglePaymentMethod(method));
    });
}

function togglePaymentMethod(element) {
    document.querySelectorAll('.payment-method').forEach(method => {
        method.classList.remove('active');
    });

    element.classList.add('active');

    const tempMethod = element.querySelector('.payment-name')?.textContent;
    const pilihButton = document.getElementById('pilihButton');

    if (tempMethod && pilihButton) {
        pilihButton.classList.add('enabled');
        pilihButton.textContent = `Pilih ${tempMethod}`;
        selectedPaymentMethod = tempMethod;
    }
}

function updateConfirmButtonState() {
    const confirmBtn = document.querySelector('.confirm-btn');
    const warnMsg = document.getElementById('warn-msg');
    const cartItems = document.querySelectorAll('.product-item');

    if (!confirmBtn) return;

    let isDisabled = false;
    let warningMessage = '';

    if (cartItems.length === 0) {
        isDisabled = true;
        warningMessage = 'Keranjang kosong';
    } else if (!selectedPaymentMethod) {
        isDisabled = true;
        warningMessage = 'Pilih metode pembayaran';
    } else if (!addressIsPresent) {
        isDisabled = true;
        warningMessage = 'Alamat belum diatur';
    }

    confirmBtn.disabled = isDisabled;
    confirmBtn.classList.toggle('disabled', isDisabled);
    
    if (warnMsg) {
        warnMsg.textContent = warningMessage;
        warnMsg.style.color = warningMessage ? '#dc3545' : '';
    }
}

function confirmPaymentMethod() {
    if (selectedPaymentMethod) {
        const openBtn = document.getElementById('openPaymentBtn');
        if (openBtn) {
            openBtn.textContent = selectedPaymentMethod;
        }
        updateConfirmButtonState();
        closePaymentModal();
        showNotification(`Metode pembayaran ${selectedPaymentMethod} dipilih`, 'success');
    }
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = 'auto';

    const pilihButton = document.getElementById('pilihButton');
    if (pilihButton) {
        pilihButton.classList.remove('enabled');
        pilihButton.textContent = 'Pilih Metode Pembayaran';
    }

    document.querySelectorAll('.payment-method').forEach(method => {
        method.classList.remove('active');
    });
}