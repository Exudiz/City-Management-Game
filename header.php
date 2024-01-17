<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management Game</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">City Management Game</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="citizens.php">Citizens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="last_notifications.php">
                            Notifications <?php echo getNotificationCount(); ?>
                        </a>
                    </li>
                    <!-- Add more menu items as needed -->
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">

<?php
// Function to get the count of notifications
function getNotificationCount() {
    require_once 'config.php';

    // Get notifications from the session
    $notifications = isset($_SESSION['notifications']) ? $_SESSION['notifications'] : [];

    // Return the count of notifications
    return count($notifications) > 0 ? "(".count($notifications).")" : "";
}
?>
