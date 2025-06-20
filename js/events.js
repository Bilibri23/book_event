document.addEventListener('DOMContentLoaded', () => {
    const eventsContainer = document.getElementById('events-container');
    const searchQuery = document.getElementById('searchQuery');
    const filterDate = document.getElementById('filterDate');
    const noResultsMessage = document.getElementById('no-results-message');
    let allEvents = [];

    fetch('php/api/events.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allEvents = data.data;
                renderEvents(allEvents, eventsContainer);
            }
        });

    document.getElementById('searchFilterForm').addEventListener('keyup', filterAndRender);
    filterDate.addEventListener('change', filterAndRender);

    function filterAndRender() {
        const query = searchQuery.value.toLowerCase();
        const date = filterDate.value;

        const filteredEvents = allEvents.filter(event => {
            const nameMatch = event.name.toLowerCase().includes(query);
            const venueMatch = event.venue.toLowerCase().includes(query);
            const organizerMatch = event.organizer.toLowerCase().includes(query);
            const dateMatch = date ? event.date === date : true;

            return (nameMatch || venueMatch || organizerMatch) && dateMatch;
        });

        renderEvents(filteredEvents, eventsContainer);
        noResultsMessage.style.display = filteredEvents.length === 0 ? 'block' : 'none';
    }
});

function renderEvents(events, container) {
    container.innerHTML = '';
    events.forEach(event => {
        const eventCard = `
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="${event.image}" class="card-img-top" alt="${event.name}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${event.name}</h5>
                        <p class="card-text">
                            <small class="text-muted">${new Date(event.date).toLocaleDateString()} at ${event.time.substring(0, 5)}</small><br>
                            <strong>${event.venue}</strong>
                        </p>
                        <p class="card-text h5 text-primary">${parseFloat(event.price).toLocaleString()} CFA</p>
                        <a href="event-details.php?id=${event.id}" class="btn btn-primary">Book Tickets</a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += eventCard;
    });
}