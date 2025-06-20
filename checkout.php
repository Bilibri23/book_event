<?php
$pageTitle = "Checkout - Eventastic";
$pageHeader = "Checkout";
$activePage = ""; // No active nav link
require_once 'templates/header.php';

// Security Check: Redirect users away if they are not logged in.
if (!isLoggedIn()) {
    header('Location: login.html');
    exit();
}
?>

    <div id="checkout-message" class="alert d-none" role="alert"></div>

    <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Your cart</span>
                <span class="badge bg-primary rounded-pill" id="cart-item-count">0</span>
            </h4>
            <ul class="list-group mb-3" id="checkout-summary-list">
            </ul>
            <div class="list-group-item d-flex justify-content-between">
                <span>Total (CFA)</span>
                <strong id="cart-total-price">0</strong>
            </div>
        </div>
        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Billing & Attendee Information</h4>
            <form id="checkoutForm" class="needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Your Full Name" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <hr class="my-4">
                <h4 class="mb-3">Payment (Simulated)</h4>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="cc-name" class="form-label">Name on card</label>
                        <input type="text" class="form-control" id="cc-name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cc-number" class="form-label">Credit card number</label>
                        <input type="text" class="form-control" id="cc-number" required>
                    </div>
                </div>
                <hr class="my-4">
                <button class="w-100 btn btn-primary btn-lg" type="submit">Confirm Booking & Pay</button>
            </form>
        </div>
    </div>

    <script src="js/toast.js"></script>
    <script src="js/checkout.js"></script>
<?php require_once 'templates/footer.php'; ?>