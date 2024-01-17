<?php
require_once 'City.php';

// Start the session
session_start();

// Ensure session variables are set
if (!isset($_SESSION['city'])) {
    // Pass the configuration settings to the City constructor
    $_SESSION['city'] = new City(require_once 'config_settings.php');
}

if (!isset($_SESSION['game_year'])) {
    $_SESSION['game_year'] = date('Y');
}

if (!isset($_SESSION['current_events'])) {
    $_SESSION['current_events'] = [];
}

// Configuration settings for the city management game
$config = require_once 'config_settings.php';

return $config;
?>
