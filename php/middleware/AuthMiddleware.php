<?php
// This file assumes session.php has already been included, which starts the session.
// It provides functions to protect pages and API endpoints.

/**
 * Checks if the current logged-in user is an administrator.
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && getCurrentUserRole() === 'admin';
}

/**
 * Checks if the logged-in user is the specific user being requested.
 * @param int $requestedUserId The ID of the user profile being accessed.
 * @return bool
 */
function isLoggedInUser($requestedUserId) {
    return isLoggedIn() && getCurrentUserId() == $requestedUserId;
}


/**
 * Requires a user to be logged in. If not, redirects or exits with an error.
 * @param bool $doRedirect If true, redirects to login page. If false, sends a JSON error.
 */
function requireUser($doRedirect = true) {
    if (!isLoggedIn()) {
        if ($doRedirect) {
            header('Location: ../login.html');
            exit();
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'message' => 'Authentication required.']);
            exit();
        }
    }
}

/**
 * Requires a user to be an admin. If not, redirects or exits with an error.
 * @param bool $doRedirect If true, redirects to homepage. If false, sends a JSON error.
 */
function requireAdmin($doRedirect = true) {
    if (!isAdmin()) {
        if ($doRedirect) {
            header('Location: ../index.php'); // Redirect non-admins to the homepage
            exit();
        } else {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'Administrator access required.']);
            exit();
        }
    }
}