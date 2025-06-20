<?php
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Cart.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];
$method = $_SERVER['REQUEST_METHOD'];

$booking = new Booking();

switch ($method) {
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : 'user_bookings'; // Default to getting user's own bookings

        if ($action === 'all_bookings') {
            requireAdmin(false); // Only admins can get all bookings
            $allBookings = $booking->getAllBookings();
            if ($allBookings !== false) {
                $response['success'] = true;
                $response['data'] = $allBookings;
            } else {
                $response['message'] = 'Could not retrieve all bookings.';
            }
        } else if ($action === 'user_bookings') {
            requireUser(false); // Any logged-in user can get their own bookings
            $userId = getCurrentUserId();
            $userBookings = $booking->getUserBookings($userId);
            if ($userBookings !== false) {
                $response['success'] = true;
                $response['data'] = $userBookings;
            } else {
                $response['message'] = 'Could not retrieve your bookings.';
            }
        } else {
            http_response_code(400);
            $response['message'] = 'Invalid action for GET request.';
        }
        break;

    case 'POST':
        requireUser(false); // Any logged-in user can create a booking
        $userId = getCurrentUserId();
        $cart = new Cart();
        $cartItems = $cart->getItems();

        if (empty($cartItems)) {
            $response['message'] = 'Your cart is empty.';
            break;
        }

        if ($booking->createBookingFromCart($userId, $cartItems)) {
            $cart->clearCart();
            $response['success'] = true;
            $response['message'] = 'Booking confirmed successfully!';
            $response['redirect'] = 'user/dashboard.php';
        } else {
            http_response_code(500);
            $response['message'] = 'Booking failed. An item may no longer be available.';
        }
        break;

    default:
        http_response_code(405);
        $response['message'] = 'Method Not Allowed';
        break;
}

echo json_encode($response);