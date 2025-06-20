<?php
// Include the configuration for BASE_URL
require_once __DIR__ . '/../config/config.php';

// Include session management to ensure the session is started
require_once __DIR__ . '/session.php';

// 1. Unset all of the session variables
$_SESSION = array();

// 2. Destroy the session itself
session_destroy();

// 3. Redirect the user to the homepage
header('Location: ' . BASE_URL . 'login.html');

// 4. Ensure no further code is executed after the redirect
exit();
?>