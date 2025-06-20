<?php
require_once __DIR__ . '/Database.php';

class Event
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllEvents()
    {
        $this->db->query('SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC');
        return $this->db->resultSet();
    }

    public function getEventById($id)
    {
        $this->db->query('SELECT * FROM events WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * NEW: Create a new event.
     * @param array $data The event data.
     * @return bool True on success.
     */
    public function createEvent($data)
    {
        $this->db->query('INSERT INTO events (name, description, date, time, venue, organizer, price, total_tickets, available_tickets, image) VALUES (:name, :description, :date, :time, :venue, :organizer, :price, :total_tickets, :available_tickets, :image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':time', $data['time']);
        $this->db->bind(':venue', $data['venue']);
        $this->db->bind(':organizer', $data['organizer']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':total_tickets', $data['total_tickets']);
        $this->db->bind(':available_tickets', $data['total_tickets']); // Available is same as total initially
        $this->db->bind(':image', isset($data['image']) ? $data['image'] : 'images/placeholders/music-fest.jpg');
        return $this->db->execute();
    }

    /**
     * NEW: Update an existing event.
     * @param int $id The ID of the event to update.
     * @param array $data The new event data.
     * @return bool True on success.
     */
    public function updateEvent($id, $data)
    {
        $this->db->query('UPDATE events SET name = :name, description = :description, date = :date, time = :time, venue = :venue, organizer = :organizer, price = :price, total_tickets = :total_tickets WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':time', $data['time']);
        $this->db->bind(':venue', $data['venue']);
        $this->db->bind(':organizer', $data['organizer']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':total_tickets', $data['total_tickets']);
        return $this->db->execute();
    }

    /**
     * NEW: Delete an event.
     * @param int $id The ID of the event to delete.
     * @return bool True on success.
     */
    public function deleteEvent($id)
    {
        // Note: In a real-world app, you might want to check for existing bookings first.
        // For this project, we assume an admin knows what they are doing.
        $this->db->query('DELETE FROM events WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

}