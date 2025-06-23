let currentMenuItem = null;
let currentQuantity = 1;

document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(function (item) {
        item.style.cursor = 'pointer';

        item.addEventListener('click', function () {
            const menuData = {
                id: item.dataset.id,
                name: item.dataset.name,
                price: parseFloat(item.dataset.price),
                stock: parseInt(item.dataset.stock),
                description: item.dataset.description,
                categories : item.dataset.categories,
                image_url: item.dataset.imageUrl || null
            };

            openMenuPopup(menuData);
        });
    });
});
function openMenuPopup(menuData) {
    currentMenuItem = menuData;
    currentQuantity = 1;

    document.getElementById('popupName').textContent = menuData.name;
    document.getElementById('popupPrice').textContent = `Rp ${menuData.price.toLocaleString('id-ID')}`;
    document.getElementById('popupStock').textContent = `Stock: ${menuData.stock}`;
    document.getElementById('popupDescription').textContent = menuData.description;
    document.getElementById('quantityDisplay').textContent = currentQuantity;
    document.getElementById('popupMenuCategory').textContent = menuData.categories.trim() !== ""
        ? menuData.categories
        : "-";

    const imageContainer = document.getElementById('popupImage');
    if (menuData.image_url) {
        imageContainer.style.backgroundImage = `url('${menuData.image_url}')`;
        imageContainer.style.backgroundSize = 'cover';
        imageContainer.style.backgroundPosition = 'center';
        imageContainer.textContent = '';
    } else {
        imageContainer.style.backgroundImage = 'none';
        imageContainer.textContent = 'No Image';
    }

    updateButtonStates();

    document.getElementById('menuPopup').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeMenuPopup() {
    document.getElementById('menuPopup').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
    currentMenuItem = null;
    currentQuantity = 1;
}

function incrementQuantity() {
    if (currentMenuItem && currentQuantity < currentMenuItem.stock) {
        currentQuantity++;
        document.getElementById('quantityDisplay').textContent = currentQuantity;
        updateButtonStates();
    }
}

function decrementQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        document.getElementById('quantityDisplay').textContent = currentQuantity;
        updateButtonStates();
    }
}

function updateButtonStates() {
    const decrementBtn = document.getElementById('decrementBtn');
    const incrementBtn = document.getElementById('incrementBtn');

    decrementBtn.disabled = currentQuantity <= 1;

    if (currentMenuItem) {
        incrementBtn.disabled = currentQuantity >= currentMenuItem.stock;
    }
}

function addToCart() {
    if (currentMenuItem) {
fetch('/user/cart/add-item', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        menu_id: currentMenuItem.id,
        quantity: currentQuantity
    })
})
.then(async response => {
    if (!response.ok) {
        const errorText = await response.text();
        console.error('Server response:', errorText);
        throw new Error('Server returned error ' + response.status);
    }
    return response.json();
})
.then(data => {
    console.log('Success:', data);
    closeMenuPopup();
})
.catch(error => {
    console.error('Fetch error:', error);
});

    }
}

document.addEventListener('click', function(event) {
    const popup = document.getElementById('menuPopup');
    const popupContainer = document.querySelector('.menu-popup-container');

    if (event.target === popup) {
        closeMenuPopup();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeMenuPopup();
    }
});
