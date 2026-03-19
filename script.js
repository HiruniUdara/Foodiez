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

    /* --- Page-Specific Initializations --- */
    if (document.getElementById('order-container')) {
        renderCartPage();
        setupOrderForm();
    }

    if (document.getElementById('contact-form')) {
        setupContactForm();
    }

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
            window.location.href = 'order.html';
        });
    });

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

function placeOrder() {
    if (cart.length === 0) {
        showToast('Your cart is empty!');
        return;
    }

    // Validate delivery address
    const addressInput = document.getElementById('delivery-address');
    const addressError = document.getElementById('address-error');
    if (addressInput) {
        const address = addressInput.value.trim();
        if (!address) {
            addressInput.classList.add('error');
            if (addressError) addressError.classList.add('visible');
            addressInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            addressInput.focus();
            return;
        } else {
            addressInput.classList.remove('error');
            if (addressError) addressError.classList.remove('visible');
        }
    }

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        showToast('Please select a payment method');
        return;
    }

    // Calculate total
    let subtotal = 0;
    cart.forEach((item) => {
        subtotal += item.price * item.quantity;
    });

    const address = addressInput ? encodeURIComponent(addressInput.value.trim()) : '';

    // Redirect to dedicated confirmation page
    window.location.href = `confirmation.html?total=${subtotal}&payment=${encodeURIComponent(paymentMethod.value)}&address=${address}`;
}

// Form Validation
function setupOrderForm() {
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (cart.length === 0) {
                showToast('Your cart is empty!');
                return;
            }

            // Basic HTML5 validation handles the required fields
            if (form.checkValidity()) {
                showOrderPopup();
            } else {
                form.reportValidity();
            }
        });
    }
}

function setupContactForm() {
    const form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (form.checkValidity()) {
                // Check if this was a checkout continuation
                const urlParams = new URLSearchParams(window.location.search);
                const total = urlParams.get('total');
                const payment = urlParams.get('payment');

                if (total && payment) {
                    showToast(`Order confirmed! Total: Rs.${total} paid via ${payment}. Thank you!`);
                    form.reset();
                    // Clear cart
                    cart = [];
                    saveCart();
                    updateCartIcon();
                    setTimeout(() => {
                        window.location.href = 'index.html';
                    }, 3000);
                } else {
                    showToast('Message sent successfully! We will get back to you soon.');
                    form.reset();
                }
            } else {
                form.reportValidity();
            }
        });

        // Auto-fill message if redirected from checkout
        const urlParams = new URLSearchParams(window.location.search);
        const total = urlParams.get('total');
        if (total) {
            const msgField = form.querySelector('textarea');
            if (msgField) {
                msgField.value = `Hello, I'd like to confirm my order. My total bill is Rs. ${total}. Please proceed with the delivery.`;
            }
        }
    }
}