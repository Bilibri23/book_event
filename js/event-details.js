// Corrected Code for: js/event-details.js

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const eventId = params.get('id');

    if (eventId) {
        // Step 1: Fetch the event data first
        fetch(`php/api/events.php?id=${eventId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const event = data.data;
                    // Step 2: Render the text content of the page
                    renderEventDetails(event);
                    // Step 3: Now that we have the venue, load the map script
                    loadMapScript(event.venue);
                } else {
                    document.getElementById('event-detail-content').innerHTML = '<div class="alert alert-danger">Error: Event not found.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching event details:', error);
                document.getElementById('event-detail-content').innerHTML = '<div class="alert alert-danger">Could not load event details. Please try again later.</div>';
            });
    }
});


function renderEventDetails(event) {
    document.title = `${event.name} - Eventastic`;
    document.querySelector('header h1').textContent = event.name;

    const content = `
        <div class="row">
            <div class="col-md-8">
                <p class="text-muted">Organized by: ${event.organizer}</p>
                <hr>
                <img src="${event.image}" alt="${event.name}" class="img-fluid rounded shadow-sm mb-4">

                <h4 class="mt-4">About this Event</h4>
                <p>${event.description.replace(/\n/g, '<br>')}</p>

                <h4 class="mt-4">When & Where</h4>
                <p><strong>Date:</strong> ${new Date(event.date).toDateString()}</p>
                <p><strong>Time:</strong> ${event.time.substring(0, 5)}</p>
                <p><strong>Venue:</strong> ${event.venue}</p>
                <div id="map" style="height: 400px; width: 100%;" class="rounded shadow-sm bg-light"></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3 sticky-top" style="top: 20px;">
                    <h4 class="card-title">Book Your Spot</h4>
                    <p class="card-text h5 text-primary">${parseFloat(event.price).toLocaleString()} CFA</p>
                    <p class="card-text text-success">Only ${event.available_tickets} tickets left!</p>
                    <div class="mb-3">
                        <label for="numTickets" class="form-label">Quantity:</label>
                        <input type="number" class="form-control" id="numTickets" value="1" min="1" max="${event.available_tickets}">
                    </div>
                    <button id="addToCartBtn" class="btn btn-primary btn-lg w-100">Add to Cart</button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('event-detail-content').innerHTML = content;
    // Add the cart listener only after the button exists on the page
    addCartListener(event.id);
}

// NEW: This function handles loading the map script and defining the callback
function loadMapScript(venueAddress) {
    // Define the initMap function and attach it to the global 'window' object
    // so the Google script can find it when it loads.
    window.initMap = function() {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': venueAddress }, function(results, status) {
            if (status === 'OK') {
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: results[0].geometry.location,
                    zoom: 15,
                });
                new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                console.error('Geocode was not successful for the following reason: ' + status);
                document.getElementById("map").innerHTML = '<div class="alert alert-warning p-5 text-center">Map could not be loaded for this location.</div>';
            }
        });
    }

    // Create a new script element
    const script = document.createElement('script');
    // IMPORTANT: Make sure to use your actual API key here
    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyATUDhmlSv6KScefAqMsSifS3WZ2uK6_Ks&callback=initMap`;
    script.async = true;
    script.defer = true;
    // Append the script to the body to start loading it
    document.body.appendChild(script);
}

function addCartListener(eventId) {
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async () => {
            const quantity = parseInt(document.getElementById('numTickets').value);
            if (quantity > 0) {
                // We assume showToast is available from toast.js which should be included in the footer
                try {
                    const response = await fetch('php/api/cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ eventId: eventId, quantity: quantity })
                    });
                    const result = await response.json();
                    if (result.success) {
                        showToast(result.message, 'success');
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('Could not connect to cart.', 'error');
                }
            }
        });
    }
}