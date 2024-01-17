<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div id="loading-spinner"></div>

<div class="container">
    <?php
	require_once 'config_settings.php';
    require_once 'config.php';
    require_once 'header.php';
    require_once 'City.php';
	
	// Pass the configuration settings to the City constructor
	$city = isset($_SESSION['city']) ? $_SESSION['city'] : new City($config);
	
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

    $city = isset($_SESSION['city']) ? $_SESSION['city'] : new City();

    if (isset($_POST['new_game'])) {
        $city = new City();
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

    <script>
        function showLoadingSpinner() {
            document.getElementById('loading-spinner').style.display = 'block';
        }

        function hideLoadingSpinner() {
            document.getElementById('loading-spinner').style.display = 'none';
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

        setInterval(simulateMonth, 600000); // 10 minutes in milliseconds

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
