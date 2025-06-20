<?php
require_once __DIR__ . '/../classes/User.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['message'] = 'Method Not Allowed';
    echo json_encode($response);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
    $response['message'] = 'All fields are required.';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email format.';
} else {
    $user = new User();
    if ($user->findByEmail($data['email'])) {
        $response['message'] = 'An account with this email already exists.';
    } else {
        if ($user->register($data)) {
            $response['success'] = true;
            $response['message'] = 'Registration successful! You can now log in.';
        } else {
            $response['message'] = 'Server error during registration. Please try again.';
        }
    }
}

echo json_encode($response);