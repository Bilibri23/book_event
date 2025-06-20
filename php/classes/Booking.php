<?php
require_once __DIR__ . '/Database.php';

class Booking {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Creates bookings from the cart items and updates event tickets within a transaction.
     * @param int $userId The ID of the user making the booking.
     * @param array $cartItems The items from the user's cart.
     * @return bool True on success, false on failure.
     */
    public function createBookingFromCart($userId, $cartItems) {
        if (empty($cartItems)) {
            return false;
        }

        // Begin a database transaction
       // $this->db->beginTransaction();

        try {
            foreach ($cartItems as $item) {
                // Step 1: Check if enough tickets are still available
                $this->db->query('SELECT available_tickets FROM events WHERE id = :event_id FOR UPDATE');
                $this->db->bind(':event_id', $item['id']);
                $event = $this->db->single();
                if (!$event || $event->available_tickets < $item['quantity']) {
                    throw new Exception('Not enough tickets for ' . $item['name']);
                }

                // Step 2: Create the booking record
                $this->db->query('INSERT INTO bookings (user_id, event_id, num_tickets, total_price) VALUES (:user_id, :event_id, :num_tickets, :total_price)');
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':event_id', $item['id']);
                $this->db->bind(':num_tickets', $item['quantity']);
                $this->db->bind(':total_price', $item['price'] * $item['quantity']);
                $this->db->execute();

                // Step 3: Update the available tickets for the event
                $this->db->query('UPDATE events SET available_tickets = available_tickets - :quantity WHERE id = :event_id');
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':event_id', $item['id']);
                $this->db->execute();
            }

            // If all queries were successful, commit the transaction
           // $this->db->commit();
            return true;

        } catch (Exception $e) {
            // If any query fails, roll back all changes
            $this->db->rollBack();
            error_log('Booking Creation Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NEW: Get all bookings for a specific user.
     * @param int $userId The ID of the user.
     * @return array An array of booking objects with event details.
     */
    public function getUserBookings($userId) {
        $this->db->query('
            SELECT 
                b.*, 
                e.name as event_name, 
                e.date as event_date, 
                e.time as event_time, 
                e.venue as event_venue,
                e.image as event_image
            FROM bookings as b
            JOIN events as e ON b.event_id = e.id
            WHERE b.user_id = :user_id
            ORDER BY b.booking_date DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * NEW: Get all bookings from all users.
     * @return array An array of all booking objects with user and event details.
     */
    public function getAllBookings() {
        $this->db->query('
            SELECT 
                b.*, 
                u.username as user_username,
                e.name as event_name
            FROM bookings as b
            JOIN users as u ON b.user_id = u.id
            JOIN events as e ON b.event_id = e.id
            ORDER BY b.booking_date DESC
        ');
        return $this->db->resultSet();
    }
}