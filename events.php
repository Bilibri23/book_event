<?php
$pageTitle = "All Events - Eventastic";
$pageHeader = "All Events";
$activePage = "events";
require_once 'templates/header.php';
?>

    <section class="event-search-filter mb-4 p-4 bg-light rounded shadow-sm">
        <h3 class="mb-3">Find Your Event</h3>
        <form id="searchFilterForm" class="row g-3">
            <div class="col-md-8">
                <input type="text" class="form-control" id="searchQuery" placeholder="Search by name, venue, or organizer...">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" id="filterDate">
            </div>
        </form>
    </section>

    <section class="event-listings">
        <div id="events-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        </div>
        <p id="no-results-message" class="alert alert-info mt-4" style="display: none;">No events match your search criteria.</p>
    </section>

    <script src="js/events.js"></script>
<?php require_once 'templates/footer.php'; ?>