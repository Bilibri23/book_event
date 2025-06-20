<?php
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../classes/Event.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'data' => []];
$method = $_SERVER['REQUEST_METHOD'];

$event = new Event();

switch ($method) {
    case 'GET':
        // This part is for regular users viewing events.
        if (isset($_GET['id'])) {
            $eventData = $event->getEventById($_GET['id']);
            if ($eventData) {
                $response['success'] = true;
                $response['data'] = $eventData;
            }
        } else {
            $allEvents = $event->getAllEvents();
            if ($allEvents) {
                $response['success'] = true;
                $response['data'] = $allEvents;
            }
        }
        break;

    case 'POST': // Creates a new event
        requireAdmin(false); // Only admins can do this
        $data = json_decode(file_get_contents("php://input"), true);
        if ($event->createEvent($data)) {
            $response['success'] = true;
            $response['message'] = 'Event created successfully!';
        } else {
            $response['message'] = 'Failed to create event.';
        }
        break;

    case 'PUT': // Updates an event
        requireAdmin(false); // Only admins can do this
        $data = json_decode(file_get_contents("php://input"), true);
        $eventId = isset($_GET['id']) ? $_GET['id'] : null;
        if ($eventId && $event->updateEvent($eventId, $data)) {
            $response['success'] = true;
            $response['message'] = 'Event updated successfully!';
        } else {
            $response['message'] = 'Failed to update event.';
        }
        break;

    case 'DELETE': // Deletes an event
        requireAdmin(false); // Only admins can do this
        $eventId = isset($_GET['id']) ? $_GET['id'] : null;
        if ($eventId && $event->deleteEvent($eventId)) {
            $response['success'] = true;
            $response['message'] = 'Event deleted successfully!';
        } else {
            $response['message'] = 'Failed to delete event.';
        }
        break;

    default:
        http_response_code(405);
        $response['message'] = 'Method Not Allowed';
        break;
}

echo json_encode($response);