<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management Game</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add some basic styling for the countdown bar */
        #countdown-bar-container {
            width: 100%;
            height: 20px;
            background-color: #ddd;
            display: none; /* Hide initially */
        }

        #countdown-bar {
            height: 100%;
            background-color: #4caf50;
        }

        #countdown-bar-title {
            margin-top: 5px;
            text-align: center;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="loading-spinner"></div>
<div class="container">
    <?php
		require_once 'config.php';
		require 'config_settings.php';
        require_once 'header.php';
		require_once 'City.php'; // Include the City class file

		$cityConfig = [
			'initial_population' => 100,
			'initial_houses' => 0,
			'initial_homeless' => 50,
			'initial_happiness' => 75,
			'initial_tax_level' => 2,
			'initial_balance' => 10000,
			'house_cost' => 150,
			'tax_per_house' => 30,
			// ... (additional configuration)
		];

		$city = new City($cityConfig);

		$config = isset($config) ? $config : []; // Provide default configuration if not set
		if (!isset($_SESSION['city'])) {
			$_SESSION['city'] = new City($config); // Pass the configuration settings to the City constructor
		}

		$city = $_SESSION['city']; // Retrieve the city object

        // Ensure session variables are set
        if (!isset($_SESSION['city'])) {
            $_SESSION['city'] = new City($config); // Pass the configuration settings to the City constructor
        }

        if (!isset($_SESSION['game_year'])) {
            $_SESSION['game_year'] = date('Y');
        }

        if (!isset($_SESSION['current_events'])) {
            $_SESSION['current_events'] = [];
        }

		$city = isset($_SESSION['city']) ? $_SESSION['city'] : new City(isset($config) ? $config : []);

        if (isset($_POST['new_game'])) {
            $city = new City('CityName');
        }

        if (isset($_POST['build_houses'])) {
            $housesToBuild = isset($_POST['houses_to_build']) ? $_POST['houses_to_build'] : 0;
            $city->buildHouses($housesToBuild);
        }

        if (isset($_POST['set_tax'])) {
            $taxLevel = isset($_POST['tax_level']) ? $_POST['tax_level'] : 2;
            $city->setTaxLevel($taxLevel);
        }

        $city->addCitizen(1, 'John Doe');
        $city->displayStats();
        $_SESSION['city'] = $city;
    ?>

    <form method="post" action="index.php">
        <label for="houses_to_build">Build Houses:</label>
        <input type="number" name="houses_to_build" id="houses_to_build" min="0" value="0">
        <input type="submit" name="build_houses" value="Build">
    </form>

    <form method="post" action="index.php">
        <label for="tax_level">Set Tax Level:</label>
        <select name="tax_level" id="tax_level">
            <option value="1" <?php echo ($city->getTaxLevel() == 1) ? 'selected' : ''; ?>>Low</option>
            <option value="2" <?php echo ($city->getTaxLevel() == 2) ? 'selected' : ''; ?>>Medium</option>
            <option value="3" <?php echo ($city->getTaxLevel() == 3) ? 'selected' : ''; ?>>High</option>
        </select>
        <input type="submit" name="set_tax" value="Set Tax">
    </form>

    <form method="post" action="index.php">
        <input type="submit" name="new_game" value="Start New Game">
    </form>

    <!-- Countdown bar elements -->
    <div id="countdown-bar-title">Time Until Next Month:</div>
    <div id="countdown-bar-container">
        <div id="countdown-bar"></div>
    </div>

    <script>
        function showLoadingSpinner() {
            document.getElementById('loading-spinner').style.display = 'block';
        }

        function hideLoadingSpinner() {
            document.getElementById('loading-spinner').style.display = 'none';
        }

        function updateCountdownBar(remainingTime) {
            var progressBar = document.getElementById('countdown-bar');
            var container = document.getElementById('countdown-bar-container');

            // Calculate the percentage of time remaining
            var percentage = (remainingTime / 600000) * 100;

            // Set the width of the progress bar
            progressBar.style.width = percentage + '%';

            // Show or hide the countdown bar based on remaining time
            if (remainingTime > 0) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        function simulateMonth() {
            showLoadingSpinner();

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "simulate_month.php", true);
            xhr.onload = function () {
                hideLoadingSpinner();

                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    displayNotifications(response.notifications);
                    updateYear(response.currentYear);
                    updateCountdownBar(600000); // Reset the countdown to 10 minutes after each simulation
                } else {
                    console.error('Error:', xhr.statusText);
                    alert('An error occurred while simulating the month. Please try again.');
                }
            };

            xhr.send();
        }

        function updateYear(year) {
            document.getElementById('current-year').textContent = 'Current Year: ' + year;
        }

        // Initial setup for countdown bar
        updateCountdownBar(600000);

        // Update the countdown bar every second
        setInterval(function () {
            var remainingTime = 600000; // Initial value, assuming the next simulation is in 10 minutes

            // Update the countdown bar
            updateCountdownBar(remainingTime);

            // Update the remaining time by subtracting 1000 milliseconds (1 second)
            remainingTime -= 1000;
        }, 1000);

        function displayNotifications(notifications) {
            notifications.forEach(function (notification) {
                var notificationDiv = document.createElement('div');
                var classType = notification.type ? 'notification ' + notification.type : 'notification';
                notificationDiv.className = classType;
                notificationDiv.textContent = notification.message;
                document.body.appendChild(notificationDiv);
            });
        }
    </script>

    <?php
    require_once 'footer.php';
    ?>
</div>
</body>
</html>
