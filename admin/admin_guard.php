<?php
require_once __DIR__ . '/../php/auth/session.php';
require_once __DIR__ . '/../php/middleware/AuthMiddleware.php';

// If requireAdmin() doesn't exist, use this basic check
if (getCurrentUserRole() !== 'admin') {
    // You can redirect to the homepage or show an access denied error
    header('Location: ../index.php');
    exit();
}