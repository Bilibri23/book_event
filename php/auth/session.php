<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if a user is logged in.
 * @return bool True if logged in, false otherwise.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get the ID of the currently logged-in user.
 * @return int|null User ID if logged in, null otherwise.
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get the role of the currently logged-in user.
 * @return string|null User role if logged in, null otherwise.
 */
function getCurrentUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}