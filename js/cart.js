document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartSummaryItems = document.getElementById('cart-summary-items');
    const cartTotalPrice = document.getElementById('cart-total-price');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const checkoutButton = document.getElementById('checkout-button');

    async function fetchCart() {
        try {
            const response = await fetch('php/api/cart.php');
            const result = await response.json();
            if (result.success) {
                renderCart(result.cart, result.total);
            }
        } catch (error) {
            console.error('Failed to fetch cart:', error);
        }
    }

    function renderCart(items, total) {
        cartItemsContainer.innerHTML = '';
        cartSummaryItems.innerHTML = '';

        if (items.length === 0) {
            emptyCartMessage.style.display = 'block';
            checkoutButton.classList.add('disabled');
        } else {
            emptyCartMessage.style.display = 'none';
            checkoutButton.classList.remove('disabled');

            items.forEach(item => {
                const itemTotal = item.price * item.quantity;
                // Main cart item display
                const cartItemHTML = `
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-2">
                                <img src="${item.image}" class="img-fluid rounded-start p-2" alt="${item.name}">
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">${item.name}</h5>
                                        <button class="btn-close remove-item" data-id="${item.id}"></button>
                                    </div>
                                    <p class="card-text">
                                        <input type="number" class="form-control form-control-sm quantity-input" value="${item.quantity}" min="1" data-id="${item.id}" style="width: 70px; display: inline-block;">
                                        x ${parseFloat(item.price).toLocaleString()} CFA
                                    </p>
                                    <p class="text-end fw-bold">Subtotal: ${itemTotal.toLocaleString()} CFA</p>
                                </div>
                            </div>
                        </div>
                    </div>`;
                cartItemsContainer.innerHTML += cartItemHTML;

                // Summary display
                const summaryItemHTML = `
                    <li class="list-group-item d-flex justify-content-between">
                        <span>${item.name} (x${item.quantity})</span>
                        <strong>${itemTotal.toLocaleString()} CFA</strong>
                    </li>`;
                cartSummaryItems.innerHTML += summaryItemHTML;
            });
        }
        cartTotalPrice.textContent = `${parseFloat(total).toLocaleString()} CFA`;
        addEventListeners();
    }

    function addEventListeners() {
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', async (e) => {
                const eventId = e.target.dataset.id;
                const quantity = parseInt(e.target.value);
                await updateCart('PUT', { eventId, quantity });
            });
        });

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', async (e) => {
                const eventId = e.target.dataset.id;
                await updateCart('DELETE', { eventId });
            });
        });
    }

    async function updateCart(method, data) {
        try {
            const response = await fetch('php/api/cart.php', {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast(result.message, 'success');
                renderCart(result.cart, result.cart.reduce((acc, item) => acc + item.price * item.quantity, 0));
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            console.error('Failed to update cart:', error);
        }
    }

    fetchCart();
});