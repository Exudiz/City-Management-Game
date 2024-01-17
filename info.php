<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management Game - How It Works</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Add any additional styles if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #555;
        }

        p {
            color: #666;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Add any additional styling as needed */
    </style>
</head>
<body>

<?php require_once 'header.php'; ?>

<div class="container">
    <h1>Welcome to the City Management Game!</h1>

    <h2>Overview</h2>
    <p>
        In this game, you take on the role of a city manager responsible for building and managing a virtual city.
        Your goal is to make strategic decisions to ensure the city's growth, happiness, and financial stability.
    </p>

    <h2>Key Features</h2>
    <ul>
        <li>Build Houses: Increase the city's population by constructing houses.</li>
        <li>Set Tax Levels: Adjust tax rates to generate revenue and influence citizen happiness.</li>
        <li>Simulate Months: Progress through the game by simulating months, triggering events, and managing resources.</li>
        <li>Attract New Citizens: Keep citizens happy to attract new residents to your city.</li>
        <li>Handle Events: Respond to random and scheduled events that impact your city.</li>
    </ul>

    <h2>How to Play</h2>
    <ol>
        <li>Use the "Build Houses" form to construct new houses and increase the city's population.</li>
        <li>Adjust the tax level using the "Set Tax Level" form to influence revenue and citizen happiness.</li>
        <li>Click "Simulate Month" to progress through the game, triggering events and managing city resources.</li>
        <li>Keep an eye on notifications for updates on events, population changes, and financial status.</li>
        <li>Explore additional features and challenges as you play!</li>
    </ol>

    <h2>Tips</h2>
    <p>
        - Balance is key. Consider the impact of your decisions on population, happiness, and finances.<br>
        - Respond to events strategically to maintain a thriving city.<br>
        - Experiment with different tax levels and house construction strategies to optimize your city's growth.
    </p>

    <p>Enjoy playing the City Management Game and have fun managing your virtual city!</p>

    <a href="index.php">Back to the Game</a>
</div>

<?php require_once 'footer.php'; ?>

</body>
</html>
