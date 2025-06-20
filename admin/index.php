<?php
require_once 'admin_guard.php'; // Secure this page
$pageTitle = "Admin Panel";
$activePage = "";
// Use a simplified header for the admin area
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Eventastic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">View Site</a></li>
                <li class="nav-item"><a class="nav-link" href="../php/auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4">Dashboard</h1>
    <p>Welcome, Admin! From here you can manage events and view all bookings.</p>

    <div class="card mt-4">
        <div class="card-header">
            <h3>Manage Events</h3>
        </div>
        <div class="card-body">
            <form id="eventForm" style="display: none;" class="mb-4 p-3 bg-light rounded">
                <input type="hidden" id="eventId">
                <h4 id="formTitle">Add New Event</h4>
                <div class="row">
                    <div class="col-md-6 mb-3"><input type="text" id="eventName" class="form-control" placeholder="Event Name" required></div>
                    <div class="col-md-6 mb-3"><input type="text" id="eventOrganizer" class="form-control" placeholder="Organizer" required></div>
                    <div class="col-12 mb-3"><textarea id="eventDescription" class="form-control" placeholder="Description" required></textarea></div>
                    <div class="col-md-6 mb-3"><input type="date" id="eventDate" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><input type="time" id="eventTime" class="form-control" required></div>
                    <div class="col-md-8 mb-3"><input type="text" id="eventVenue" class="form-control" placeholder="Venue" required></div>
                    <div class="col-md-4 mb-3"><input type="number" id="eventPrice" class="form-control" placeholder="Price (CFA)" required></div>
                    <div class="col-md-6 mb-3"><input type="number" id="eventTickets" class="form-control" placeholder="Total Tickets" required></div>
                    <div class="col-md-6 mb-3"><input type="text" id="eventImage" class="form-control" placeholder="Image Path (e.g., images/events/new.jpg)"></div>
                </div>
                <button type="submit" class="btn btn-success">Save Event</button>
                <button type="button" id="cancelEdit" class="btn btn-secondary">Cancel</button>
            </form>

            <button id="showEventFormBtn" class="btn btn-primary mb-3">Add New Event</button>
            <table class="table table-striped">
                <thead><tr><th>Name</th><th>Date</th><th>Venue</th><th>Price</th><th>Tickets</th><th>Actions</th></tr></thead>
                <tbody id="eventsTableBody"></tbody>
            </table>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            <h3>View All Bookings</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead><tr><th>Booking ID</th><th>Event</th><th>User</th><th>Tickets</th><th>Total Price</th><th>Date</th></tr></thead>
                <tbody id="bookingsTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="../js/admin.js"></script>
</body>
</html>