<?php
$pageTitle = "My Dashboard - Eventastic";
$pageHeader = "My Dashboard";
$activePage = ""; // No active nav link
// We are already inside the 'user' folder, so paths need to go up one level
require_once __DIR__ . '/../templates/header.php';

// Security Check: Redirect users away if they are not logged in.
if (!isLoggedIn()) {
    header('Location: ../login.html');
    exit();
}
?>

    <div id="user-bookings-list" class="row row-cols-1 row-cols-md-2 g-4">
    </div>
    <div id="no-bookings-message" class="alert alert-info" style="display: none;">
        You have no bookings yet. <a href="../events.php">Find an event to book!</a>
    </div>

    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Your Event Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h4 id="ticket-event-name" class="text-primary"></h4>
                    <p class="mb-1"><strong>Booking ID:</strong> <span id="ticket-booking-id"></span></p>
                    <p class="text-muted">Booked on <span id="ticket-booking-date"></span></p>
                    <hr>
                    <div class="text-start">
                        <p><strong>Date & Time:</strong> <span id="ticket-event-date"></span></p>
                        <p><strong>Venue:</strong> <span id="ticket-event-venue"></span></p>
                        <p><strong>Tickets:</strong> <span id="ticket-num-tickets"></span></p>
                    </div>
                    <div id="qrcode" class="my-3 d-flex justify-content-center"></div>
                    <p class="text-muted small">Present this ticket at the event entrance.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="../js/dashboard.js"></script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>