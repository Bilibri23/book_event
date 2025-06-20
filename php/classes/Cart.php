<?php
require_once __DIR__ . '/Event.php';

class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function addItem($eventId, $quantity) {
        $event = new Event();
        $eventData = $event->getEventById($eventId);

        if (!$eventData || $quantity <= 0) {
            return ['error' => 'Invalid event or quantity.'];
        }

        if ($quantity > $eventData->available_tickets) {
            return ['error' => 'Not enough tickets available.'];
        }

        if (isset($_SESSION['cart'][$eventId])) {
            // Update quantity if item is already in cart
            $_SESSION['cart'][$eventId]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $_SESSION['cart'][$eventId] = [
                'id' => $eventData->id,
                'name' => $eventData->name,
                'price' => $eventData->price,
                'image' => $eventData->image,
                'quantity' => $quantity
            ];
        }
        return $this->getItems();
    }

    public function updateItemQuantity($eventId, $quantity) {
        if (!isset($_SESSION['cart'][$eventId])) {
            return false;
        }
        if ($quantity <= 0) {
            $this->removeItem($eventId);
        } else {
            $_SESSION['cart'][$eventId]['quantity'] = $quantity;
        }
        return $this->getItems();
    }

    public function removeItem($eventId) {
        unset($_SESSION['cart'][$eventId]);
        return $this->getItems();
    }

    public function getItems() {
        return array_values(isset($_SESSION['cart']) ? $_SESSION['cart'] : []); // Return as a simple array
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getItemCount() {
        return count($_SESSION['cart']);
    }

    public function clearCart() {
        $_SESSION['cart'] = [];
    }
}