<?php
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../classes/Cart.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$cart = new Cart();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $response['success'] = true;
        $response['cart'] = $cart->getItems();
        $response['total'] = $cart->getTotal();
        $response['item_count'] = $cart->getItemCount();
        break;

    case 'POST': // Add item to cart
        $data = json_decode(file_get_contents('php://input'), true);
        $eventId = isset($data['eventId']) ? $data['eventId'] : null;
        $quantity = isset($data['quantity']) ? $data['quantity'] : 0;

        $result = $cart->addItem($eventId, $quantity);
        if (isset($result['error'])) {
            $response['message'] = $result['error'];
        } else {
            $response['success'] = true;
            $response['message'] = 'Item added to cart!';
            $response['cart'] = $result;
        }
        break;

    case 'PUT': // Update item quantity
        $data = json_decode(file_get_contents('php://input'), true);
        $eventId = isset($data['eventId']) ? $data['eventId'] : null;
        $quantity = isset($data['quantity']) ? $data['quantity'] : 0;

        $result = $cart->updateItemQuantity($eventId, $quantity);
        if ($result !== false) {
            $response['success'] = true;
            $response['message'] = 'Cart updated!';
            $response['cart'] = $result;
        } else {
            $response['message'] = 'Item not found in cart.';
        }
        break;

    case 'DELETE': // Remove item
        $data = json_decode(file_get_contents('php://input'), true);
        $eventId = isset($data['eventId']) ? $data['eventId'] : null;

        $result = $cart->removeItem($eventId);
        $response['success'] = true;
        $response['message'] = 'Item removed from cart.';
        $response['cart'] = $result;
        break;

    default:
        http_response_code(405);
        $response['message'] = 'Method Not Allowed';
        break;
}

echo json_encode($response);