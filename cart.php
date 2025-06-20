<?php
$pageTitle = "Your Cart - Eventastic";
$pageHeader = "Your Booking Cart";
$activePage = "cart";
require_once 'templates/header.php';
?>

    <div id="cart-message" class="alert d-none" role="alert"></div>

    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4">Items in your cart</h2>
            <div id="cart-items-container">
            </div>
            <p id="empty-cart-message" class="text-muted text-center" style="display: none;">
                Your cart is empty. <a href="events.php">Browse events</a> to add items.
            </p>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h3 class="card-title mb-3">Order Summary</h3>
                    <ul class="list-group list-group-flush" id="cart-summary-items">
                    </ul>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0 text-primary" id="cart-total-price">0 CFA</h5>
                    </div>
                    <a href="checkout.php" id="checkout-button" class="btn btn-success w-100 btn-lg mt-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="js/toast.js"></script>
    <script src="js/cart.js"></script>
<?php require_once 'templates/footer.php'; ?>