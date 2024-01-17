<?php

require_once 'config.php';
require_once 'City.php';

if (!isset($_SESSION['city'])) {
    echo json_encode(['notifications' => [['message' => 'City not found in session.', 'type' => 'error']]]);
    exit;
}

$city = $_SESSION['city'];
$city->simulateMonth();
$notifications = $city->getNotifications();
$city->clearNotifications();

// Preserve currentMonth and city in the session
$_SESSION['city'] = $city;

echo json_encode(['notifications' => $notifications]);
echo "Month simulated successfully.";
?>