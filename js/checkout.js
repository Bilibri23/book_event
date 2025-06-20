document.addEventListener('DOMContentLoaded', () => {
    const summaryList = document.getElementById('checkout-summary-list');
    const itemCountBadge = document.getElementById('cart-item-count');
    const totalPriceDisplay = document.getElementById('cart-total-price');
    const checkoutForm = document.getElementById('checkoutForm');
    const checkoutMessage = document.getElementById('checkout-message');

    // 1. Fetch and display the cart summary on page load
    async function loadCartSummary() {
        try {
            const response = await fetch('php/api/cart.php');
            const result = await response.json();
            if (result.success && result.cart.length > 0) {
                summaryList.innerHTML = '';
                result.cart.forEach(item => {
                    const listItem = `
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">${item.name}</h6>
                                <small class="text-muted">Quantity: ${item.quantity}</small>
                            </div>
                            <span class="text-muted">${(item.price * item.quantity).toLocaleString()} CFA</span>
                        </li>`;
                    summaryList.innerHTML += listItem;
                });
                itemCountBadge.textContent = result.item_count;
                totalPriceDisplay.textContent = result.total.toLocaleString();
            } else {
                // If cart is empty, redirect back to cart page
                window.location.href = 'cart.php';
            }
        } catch (error) {
            console.error('Failed to load cart summary:', error);
        }
    }

    // 2. Handle the final form submission
    checkoutForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        // Here you would normally handle payment processing with Stripe, etc.
        // For this project, we'll just confirm the booking.

        showToast('Processing your booking...', 'success');

        try {
            const response = await fetch('php/api/bookings.php', { method: 'POST' });
            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                // Redirect to the dashboard on success
                setTimeout(() => { window.location.href = result.redirect; }, 2000);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('A critical error occurred. Please try again.', 'error');
        }
    });

    // Initial load
    loadCartSummary();
});