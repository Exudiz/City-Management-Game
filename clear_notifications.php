<?php
// clear_notifications.php

require_once 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_notifications'])) {
    // Clear notifications in the session
    $_SESSION['notifications'] = [];

    // Redirect back to the last_notifications.php page
    header("Location: last_notifications.php");
    exit;
} else {
    // Handle other cases or redirect appropriately
}
?>
