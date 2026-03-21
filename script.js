/* 
   ==========================================================================
   STATE MANAGEMENT
   Handles the persistent cart data using localStorage.
   ========================================================================== 
*/
let cart = JSON.parse(localStorage.getItem('foodDeliveryCart')) || [];

/* 
   ==========================================================================
   DOM INITIALIZATION
   Sets up event listeners and page-specific logic once the DOM is ready.
   ========================================================================== 
*/
document.addEventListener('DOMContentLoaded', () => {
    updateCartIcon();
    setupNavigation();

    /* --- Add to Cart Integration --- */
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const card = e.target.closest('.food-card');
            const id = card.dataset.id;
            const name = card.dataset.name;
            const price = parseFloat(card.dataset.price);
            const img = card.dataset.img;

            let qty = 1;
            const qtyInput = card.querySelector('.qty-input');
            if (qtyInput) {
                qty = parseInt(qtyInput.value) || 1;
            }

            addToCart({ id, name, price, img, quantity: qty });

            // Smooth redirect to the checkout experience
            window.location.href = 'order.php';
        });
    });

    /* --- Page-Specific Initializations --- */
    if (document.getElementById('order-container')) {
        renderCartPage();
    }

    /* --- Product Quantity Controls --- */
    const qtyControls = document.querySelectorAll('.qty-controls');
    qtyControls.forEach(control => {
        const minusBtn = control.querySelector('.qty-minus');
        const plusBtn = control.querySelector('.qty-plus');
        const input = control.querySelector('.qty-input');

        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                if (val > 1) input.value = val - 1;
            });
            plusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                input.value = val + 1;
            });
        }
    });
});

/* 
   ==========================================================================
   NAVIGATION & MOBILE MENU
   Toggles the navigation drawer on mobile viewports.
   ========================================================================== 
*/
function setupNavigation() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }
}

// Cart Functions
function addToCart(item) {
    const existingItemIndex = cart.findIndex(c => c.id === item.id);
    if (existingItemIndex > -1) {
        cart[existingItemIndex].quantity += item.quantity;
    } else {
        cart.push(item);
    }
    saveCart();
    updateCartIcon();
}

function updateCartIcon() {
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountEl.textContent = totalItems;
    }
}

function saveCart() {
    localStorage.setItem('foodDeliveryCart', JSON.stringify(cart));
}

// Notifications
function showToast(message) {
    let toast = document.getElementById('toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-notification';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function showOrderPopup() {
    const overlay = document.getElementById('order-popup');
    if (overlay) {
        overlay.classList.add('active');
    }
}

function closePopup() {
    const overlay = document.getElementById('order-popup');
    if (overlay) {
        overlay.classList.remove('active');
        // Clear cart and redirect to home after close
        cart = [];
        saveCart();
        window.location.href = 'index.html';
    }
}

// Order Page Logic
function renderCartPage() {
    const container = document.getElementById('order-container');
    const totalDisplay = document.getElementById('total-price');

    if (!container) return; // Not on the order page

    container.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = `<div style="text-align:center; padding: 2rem; color: #64748b;">No items in your cart yet. Head to the Menu to order!</div>`;
        if (totalDisplay) totalDisplay.textContent = `Rs. 0`;
        return;
    }

    let subtotal = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const ratingStars = Math.floor(Math.random() * 2) + 3; // Random 3, 4, or 5 star for aesthetics
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `<svg viewBox="0 0 24 24" class="${i > ratingStars ? 'star-empty' : ''}"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>`;
        }

        const itemEl = document.createElement('div');
        itemEl.className = 'order-item-card';
        itemEl.innerHTML = `
            <div class="order-item-img">
              <img src="${item.img}" alt="${item.name}">
            </div>
            <div class="order-item-details">
              <div class="order-item-header">
                  <h3 class="order-item-title">${item.name}</h3>
                  <button class="remove-item-btn" onclick="removeFromCart(${index})">
                      <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
              </div>
              <div class="order-item-stars">
                ${starsHtml}
              </div>
              <div class="order-item-divider"></div>
              <div class="order-item-bottom">
                <div class="order-item-price"><span>Rs.</span> ${item.price}</div>
                <div class="order-qty-controls">
                    <button class="qty-ctrl-btn" onclick="updateCartItemQty(${index}, -1)">-</button>
                    <span class="qty-value">${item.quantity}</span>
                    <button class="qty-ctrl-btn" onclick="updateCartItemQty(${index}, 1)">+</button>
                </div>
              </div>
            </div>
          `;
        container.appendChild(itemEl);
    });

    if (totalDisplay) {
        totalDisplay.textContent = `Rs. ${subtotal}`;
    }
}

window.updateCartItemQty = function (index, change) {
    if (cart[index].quantity + change > 0) {
        cart[index].quantity += change;
    } else if (cart[index].quantity + change === 0) {
        cart.splice(index, 1);
    }
    saveCart();
    updateCartIcon();
    renderCartPage();
};

window.removeFromCart = function (index) {
    cart.splice(index, 1);
    saveCart();
    updateCartIcon();
    renderCartPage();
};