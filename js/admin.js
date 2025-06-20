document.addEventListener('DOMContentLoaded', () => {
    // --- Globals ---
    const eventsTableBody = document.getElementById('eventsTableBody');
    const bookingsTableBody = document.getElementById('bookingsTableBody');
    const eventForm = document.getElementById('eventForm');
    const showEventFormBtn = document.getElementById('showEventFormBtn');
    const cancelEditBtn = document.getElementById('cancelEdit');
    const formTitle = document.getElementById('formTitle');

    // --- Fetch and Render Functions ---
    async function fetchEvents() {
        const response = await fetch('../php/api/events.php');
        const { data } = await response.json();
        eventsTableBody.innerHTML = '';
        data.forEach(event => {
            eventsTableBody.innerHTML += `
                <tr>
                    <td>${event.name}</td>
                    <td>${event.date}</td>
                    <td>${event.venue}</td>
                    <td>${event.price} CFA</td>
                    <td>${event.available_tickets}/${event.total_tickets}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-event='${JSON.stringify(event)}'>Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${event.id}">Delete</button>
                    </td>
                </tr>`;
        });
    }

    async function fetchBookings() {
        const response = await fetch('../php/api/bookings.php?action=all_bookings');
        const { data } = await response.json();
        bookingsTableBody.innerHTML = '';
        data.forEach(booking => {
            bookingsTableBody.innerHTML += `
                <tr>
                    <td>${booking.id}</td>
                    <td>${booking.event_name}</td>
                    <td>${booking.user_username}</td>
                    <td>${booking.num_tickets}</td>
                    <td>${booking.total_price} CFA</td>
                    <td>${new Date(booking.booking_date).toLocaleString()}</td>
                </tr>`;
        });
    }

    // --- Form Handling ---
    eventForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const eventId = document.getElementById('eventId').value;
        const eventData = {
            name: document.getElementById('eventName').value,
            organizer: document.getElementById('eventOrganizer').value,
            description: document.getElementById('eventDescription').value,
            date: document.getElementById('eventDate').value,
            time: document.getElementById('eventTime').value,
            venue: document.getElementById('eventVenue').value,
            price: document.getElementById('eventPrice').value,
            total_tickets: document.getElementById('eventTickets').value,
            image: document.getElementById('eventImage').value,
        };

        const method = eventId ? 'PUT' : 'POST';
        const url = eventId ? `../php/api/events.php?id=${eventId}` : '../php/api/events.php';

        await fetch(url, {
            method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(eventData)
        });

        resetForm();
        fetchEvents();
    });

    // --- Event Listeners ---
    showEventFormBtn.addEventListener('click', () => {
        resetForm();
        formTitle.textContent = 'Add New Event';
        eventForm.style.display = 'block';
    });

    cancelEditBtn.addEventListener('click', () => {
        resetForm();
    });

    eventsTableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) {
            const event = JSON.parse(e.target.dataset.event);
            formTitle.textContent = 'Edit Event';
            document.getElementById('eventId').value = event.id;
            document.getElementById('eventName').value = event.name;
            document.getElementById('eventOrganizer').value = event.organizer;
            document.getElementById('eventDescription').value = event.description;
            document.getElementById('eventDate').value = event.date;
            document.getElementById('eventTime').value = event.time;
            document.getElementById('eventVenue').value = event.venue;
            document.getElementById('eventPrice').value = event.price;
            document.getElementById('eventTickets').value = event.total_tickets;
            document.getElementById('eventImage').value = event.image;
            eventForm.style.display = 'block';
            window.scrollTo(0, 0);
        }

        if (e.target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this event?')) {
                const eventId = e.target.dataset.id;
                fetch(`../php/api/events.php?id=${eventId}`, { method: 'DELETE' })
                    .then(() => fetchEvents());
            }
        }
    });

    function resetForm() {
        eventForm.reset();
        document.getElementById('eventId').value = '';
        eventForm.style.display = 'none';
    }

    // --- Initial Load ---
    fetchEvents();
    fetchBookings();
});