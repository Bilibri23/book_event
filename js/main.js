document.addEventListener('DOMContentLoaded', () => {
    const eventsContainer = document.getElementById('events-container');

    fetch('php/api/events.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show only the first 3 events on the homepage
                const limitedEvents = data.data.slice(0, 3);
                renderEvents(limitedEvents, eventsContainer);
            }
        });
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
                        <p class="card-text text-muted">${new Date(event.date).toLocaleDateString()}</p>
                        <a href="event-details.php?id=${event.id}" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += eventCard;
    });
}