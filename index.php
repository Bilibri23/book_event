<?php
$pageTitle = "Home - Eventastic";
$pageHeader = "Welcome to Eventastic!";
$pageSubtitle = "Your one-stop solution for event bookings.";
$activePage = "home";
require_once 'templates/header.php';
?>

    <section class="jumbotron text-center bg-light p-5 rounded shadow-sm">
        <h2 class="display-4">Discover Amazing Events!</h2>
        <p class="lead">Browse, search, and book tickets for various events effortlessly.</p>
        <hr class="my-4">
        <a class="btn btn-primary btn-lg" href="events.php" role="button">Browse All Events</a>
    </section>

    <section class="recent-events my-5">
        <h3 class="text-center mb-4">Upcoming Events</h3>
        <div id="events-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        </div>
    </section>

    <script src="js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>