<!-- citizens.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizens</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">

        <?php
        require_once 'config.php';
        require_once 'header.php';
        require_once 'City.php';

        $city = isset($_SESSION['city']) ? $_SESSION['city'] : new City();

        // Add a search box at the top
        ?>
        <form method="get" action="citizens.php">
            <label for="search">Search Citizens:</label>
            <input type="text" name="search" id="search" placeholder="Enter citizen name">
            <input type="submit" value="Search">
        </form>

        <!-- Display Citizens -->
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $city->displayCitizens($search);

        // Preserve currentMonth and city in the session
        $_SESSION['city'] = $city;
        ?>

        <a href="index.php">Back to City Management</a>

        <?php
        require_once 'footer.php';
        ?>
    </div>
</body>
</html>
