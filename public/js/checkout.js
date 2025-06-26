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
    const display = button.parentElement.querySelector('.quantity-display');
    const quantity = parseInt(display.textContent);
    const stock = parseInt(button.dataset.stock);

    if (!cartId) return console.error('No cart ID provided');
    const newQuantity = isIncrement ? quantity + 1 : quantity - 1;

    if (!isIncrement && quantity === 1) {
        await deleteCartItem(cartId);
    } else if (newQuantity <= stock) {
        await updateCartQuantity(cartId, newQuantity, display);
    }
}

async function updateCartQuantity(cartId, quantity, display) {
    try {
        const response = await fetch(`/cart/${cartId}/quantity`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ quantity })
        });

        if (!response.ok) throw new Error("Server error");

        const data = await response.json();
        display.textContent = data.quantity;
        updatePriceSummary(data);
    } catch (error) {
        console.error("Failed to update quantity:", error);
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
    if (!cartId) return console.error('No cart ID found');
    deleteCartItem(cartId);
}

async function deleteCartItem(cartId) {
    try {
        const response = await fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Failed to delete item');

        const data = await response.json();
        document.querySelector(`.product-item[data-id="${cartId}"]`)?.remove();
        updatePriceSummary(data);
    } catch (error) {
        console.error('Failed to delete:', error);
    }
}

function updatePriceSummary(data) {
    document.querySelector('.total-text').textContent = `Total Pesanan : Rp ${formatRupiah(data.subtotal)}`;
    const rows = document.querySelectorAll('.price-breakdown .price-row span:nth-child(2)');
    rows[0].textContent = `Rp ${formatRupiah(data.subtotal)}`;
    rows[1].textContent = `Rp ${formatRupiah(data.shipping)}`;
    rows[2].textContent = `Rp ${formatRupiah(data.adminFee)}`;
    document.querySelector('.total-row span:nth-child(2)').textContent = `Rp ${formatRupiah(data.total)}`;
}

function formatRupiah(number) {
    return number.toLocaleString('id-ID');
}

function bindConfirmButton() {
    const confirmBtn = document.querySelector('.confirm-btn');
    if (!confirmBtn) return;

    if (selectedPaymentMethod === null) {
        confirmBtn.classList.add('disabled');
    }

    confirmBtn.addEventListener('click', async () => {
        const notes = document.querySelector('.notes-input')?.value.trim() || '';
        console.log("Selected payment method:", selectedPaymentMethod);
        console.log("notes:", notes);

        try {
            const response = await fetch('/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    payment_method: selectedPaymentMethod,
                    notes: notes
                })
            });

            const result = await response.json();
            console.log(result)
            window.location.replace(`/order/${result.order_id}`);
        } catch (err) {
            console.error('Error:', err);
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

    if (cartItems.length === 0) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('disabled');
        if (warnMsg) warnMsg.textContent = 'Keranjang kosong';
    } else if (!selectedPaymentMethod) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('disabled');
        if (warnMsg) warnMsg.textContent = 'Pilih metode pembayaran';
    } else if (!addressIsPresent) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('disabled');
        if (warnMsg) warnMsg.textContent = 'Alamat Belum Diatur';
    } else {
        confirmBtn.disabled = false;
        confirmBtn.classList.remove('disabled');
        if (warnMsg) warnMsg.textContent = '';
    }
}



function confirmPaymentMethod() {
    if (selectedPaymentMethod) {
        const openBtn = document.getElementById('openPaymentBtn');
        if (openBtn) {
            openBtn.textContent = `${selectedPaymentMethod}`;
        }
        updateConfirmButtonState();
        closePaymentModal();
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
