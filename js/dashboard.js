document.addEventListener('DOMContentLoaded', () => {
    const bookingsListContainer = document.getElementById('user-bookings-list');
    const noBookingsMessage = document.getElementById('no-bookings-message');
    const ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));

    async function fetchAndRenderBookings() {
        try {
            const response = await fetch('../php/api/bookings.php?action=user_bookings');
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                bookingsListContainer.innerHTML = '';
                result.data.forEach(booking => {
                    const bookingCard = createBookingCard(booking);
                    bookingsListContainer.insertAdjacentHTML('beforeend', bookingCard);
                });
                addTicketButtonListeners();
            } else {
                noBookingsMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Failed to fetch bookings:', error);
            noBookingsMessage.textContent = 'Could not load your bookings. Please try again later.';
            noBookingsMessage.style.display = 'block';
        }
    }

    function createBookingCard(booking) {
        const eventDate = new Date(booking.event_date).toDateString();
        const bookingDate = new Date(booking.booking_date).toLocaleDateString();
        const eventTime = booking.event_time.substring(0, 5);

        return `
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="row g-0">
                        <div class="col-4">
                             <img src="${booking.event_image.replace('../', '')}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="${booking.event_name}">
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title">${booking.event_name}</h5>
                                <p class="card-text mb-1">
                                    <small class="text-muted">${eventDate} at ${eventTime}</small>
                                </p>
                                <p class="card-text">
                                    <strong>${booking.num_tickets} Tickets</strong> - ${booking.venue}
                                </p>
                                <button class="btn btn-primary btn-sm view-ticket-btn" 
                                    data-booking='${JSON.stringify(booking)}'>
                                    View Ticket & QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    function addTicketButtonListeners() {
        document.querySelectorAll('.view-ticket-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const bookingData = JSON.parse(e.currentTarget.dataset.booking);
                populateAndShowTicket(bookingData);
            });
        });
    }

    function populateAndShowTicket(booking) {
        // Populate modal text
        document.getElementById('ticket-event-name').textContent = booking.event_name;
        document.getElementById('ticket-booking-id').textContent = booking.id;
        document.getElementById('ticket-booking-date').textContent = new Date(booking.booking_date).toLocaleDateString();
        document.getElementById('ticket-event-date').textContent = `${new Date(booking.event_date).toDateString()} at ${booking.event_time.substring(0, 5)}`;
        document.getElementById('ticket-event-venue').textContent = booking.event_venue;
        document.getElementById('ticket-num-tickets').textContent = booking.num_tickets;

        // Generate QR Code
        const qrcodeContainer = document.getElementById('qrcode');
        qrcodeContainer.innerHTML = ''; // Clear previous QR code
        const qrData = `BookingID:${booking.id}|Event:${booking.event_name}|Tickets:${booking.num_tickets}`;
        new QRCode(qrcodeContainer, {
            text: qrData,
            width: 150,
            height: 150,
        });

        // Show the modal
        ticketModal.show();
    }

    fetchAndRenderBookings();
});