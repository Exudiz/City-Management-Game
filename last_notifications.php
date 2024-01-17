<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php
        require_once 'config.php';
        require_once 'header.php';

        // Check if the notifications array exists in the session
        if (isset($_SESSION['notifications'])) {
            // Display 50 notifications per page
            $notificationsPerPage = 50;

            // Get the page number from the URL, default to 1
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

            // Get all notifications
            $notifications = array_reverse($_SESSION['notifications']);

            // Calculate the total number of pages
            $totalPages = ceil(count($notifications) / $notificationsPerPage);

            // Calculate the starting index for the current page
            $startIndex = ($currentPage - 1) * $notificationsPerPage;

			// Display notifications for the current page
			for ($i = $startIndex; $i < min($startIndex + $notificationsPerPage, count($notifications)); $i++) {
				$notification = $notifications[$i];
				$message = $notification['message'];
				$type = $notification['type'];
				$timestamp = date('d-m-y | H:i:s', $notification['timestamp']);

				echo "<div class='notification $type'>";
				echo "<p><strong>$timestamp:</strong> $message</p>";
				echo "</div>";
			}

            // Display pagination links
            echo "<div class='pagination'>";
            for ($page = 1; $page <= $totalPages; $page++) {
                echo "<a href='last_notifications.php?page=$page'>$page</a>";
            }
            echo "</div>";

            // Display "Clear Notifications" button
            echo "<form method='post' action='clear_notifications.php'>";
            echo "<input type='submit' name='clear_notifications' value='Clear Notifications'>";
            echo "</form>";

            // Don't clear notifications here
        } else {
            echo "<p>No notifications available.</p>";
        }
        ?>
        <?php
        require_once 'footer.php';
        ?>
    </div>
</body>
</html>
