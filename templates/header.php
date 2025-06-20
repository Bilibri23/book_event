<?php
require_once __DIR__ . '/../php/auth/session.php';
require_once __DIR__ . '/../php/config/config.php'; // We need BASE_URL
global $activePage;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Eventastic'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css"> </head>
<body>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="appToast" class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<header class="bg-primary text-white text-center py-3">
    <h1><?php echo isset($pageHeader) ? $pageHeader : 'Welcome to Eventastic!'; ?></h1>
    <?php if (isset($pageSubtitle)): ?><p><?php echo $pageSubtitle; ?></p><?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">Eventastic</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage === 'home') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage === 'events') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage === 'cart') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>cart.php">Cart</a>
                    </li>

                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>user/dashboard.php">Dashboard</a>
                        </li>
                        <?php if (getCurrentUserRole() === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>admin/index.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>php/auth/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>login.html">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>register.html">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container my-5">