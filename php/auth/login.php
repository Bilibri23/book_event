<?php
require_once __DIR__ . '/session.php'; // Includes session_start()
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['message'] = 'Method Not Allowed';
    echo json_encode($response);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['email']) || empty($data['password'])) {
    $response['message'] = 'Email and password are required.';
} else {
    $user = new User();
    $loggedInUser = $user->login($data['email'], $data['password']);

    if ($loggedInUser) {
        // Set session variables
        $_SESSION['user_id'] = $loggedInUser->id;
        $_SESSION['user_username'] = $loggedInUser->username;
        $_SESSION['user_role'] = $loggedInUser->role;

        $response['success'] = true;
        $response['message'] = 'Login successful!';
        // Send redirect URL based on role
        if ($loggedInUser->role === 'admin') {
            $response['redirect'] = BASE_URL . 'admin/index.php';
        } else {
            $response['redirect'] = BASE_URL . 'index.php';
        }
    } else {
        $response['message'] = 'Invalid email or password.';
    }
}

echo json_encode($response);