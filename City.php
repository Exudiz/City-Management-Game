<?php

require_once 'config_settings.php';
require_once 'config.php';

if (!isset($_SESSION['city'])) {
    $_SESSION['city'] = new City($config); // Pass the configuration settings to the City constructor
}

$city = $_SESSION['city']; // Add this line to retrieve the city object

// Example: Schedule an event if the city's population exceeds 1000
$city->scheduleEvent('Festival', $city->getPopulation() > 1000, 'A festival is happening in the city!');

// Example: Schedule an event if the city's balance drops below 500
$city->scheduleEvent('Recession', $city->getBalance() < 500, 'The city is facing an economic recession!');

// Example: Schedule a yearly anniversary celebration event
$city->scheduleEvent('Anniversary', $city->getCurrentMonth() == 1, 'Happy city anniversary!');

class City
{
    private $population;
    private $houses;
    private $homeless;
    private $happiness;
    private $currentMonth;
    private $startingYear;
    private $taxLevel;
    private $balance;
    private $notifications = [];
    private $citizens = [];
    private $houseCost = 100; // Set the cost to build one house
    private $taxPerHouse = 20; // Set the tax collected per house
    private $scheduledEvents = [];

    public function __construct($config)
    {
        $this->population = $config['initial_population'];
        $this->houses = $config['initial_houses'];
        $this->homeless = $config['initial_homeless'];
        $this->happiness = $config['initial_happiness'];
        $this->taxLevel = $config['initial_tax_level'];
        $this->balance = $config['initial_balance'];
        $this->houseCost = $config['house_cost'];
        $this->taxPerHouse = $config['tax_per_house'];
        // ... (additional initialization)
    }
	
    public function scheduleEvent($eventName, $condition, $message)
    {
        $this->scheduledEvents[] = [
            'eventName' => $eventName,
            'condition' => $condition,
            'message' => $message,
        ];
    }

    private function checkAndTriggerScheduledEvents()
    {
        if (!method_exists($this, 'checkAndTriggerScheduledEventsInternal')) {
            $this->checkAndTriggerScheduledEventsInternal = function () {
                foreach ($this->scheduledEvents as $key => $event) {
                    if ($this->evaluateEventCondition($event['condition'])) {
                        $this->triggerEvent($event['message'], 'event');
                        unset($this->scheduledEvents[$key]);
                    }
                }
            };
        }

        ($this->checkAndTriggerScheduledEventsInternal)->call($this);
    }

    public function getCurrentYear()
    {
        $currentYear = date('Y');
        $yearsPassed = $currentYear - $this->startingYear;

        return max(0, $yearsPassed);
    }

    public function getPopulation()
    {
        return $this->population;
    }
	
	public function getBalance()
    {
        return $this->balance;
    }
	
	public function getTaxLevel()
    {
        return $this->taxLevel;
    }

    public function buildHouses($count)
    {
        $count = max(0, (int)$count);
        $_SESSION['build_success'] = true;

        if ($count > 0) {
            $totalCost = $count * $this->houseCost;

            if ($this->balance >= $totalCost) {
                $this->houses += $count;
                $this->balance -= $totalCost;
                $this->updatePopulation();
                $this->addNotification("Built {$count} houses! The city is growing.", 'success');
            } else {
                $this->addNotification("Not enough funds to build houses. Taxes have been lowered.", 'error');
                $this->lowerTaxes();
            }
        } else {
            $this->addNotification("Invalid number of houses to build.", 'error');
        }
    }

    public function setTaxLevel($level)
    {
        $taxLevels = ['Low', 'Medium', 'High'];
        $this->taxLevel = $level;
        $this->addNotification("Changed tax level to {$taxLevels[$level - 1]}.", 'info');
        $_SESSION['city'] = $this; // Save the updated city in the session
    }

    public function simulateMonth()
    {
        $this->updatePopulation();
        $this->updateHappiness();
        $this->collectTaxes();
        $this->attractNewCitizens();
        $this->updateCurrentMonth();
        $this->checkAndTriggerScheduledEvents(); // Added event handling
        $this->checkAndTriggerRandomEvent();
    }

    public function displayStats()
    {
        $this->displayNotifications();

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $currentMonth = $months[$this->currentMonth - 1];
        $taxLevels = ['Low', 'Medium', 'High'];

        echo "<p>Current Month: {$currentMonth}</p>";
        echo "<p>Current Year: " . ($this->startingYear + $this->getCurrentYear()) . "</p>"; // Use $startingYear and getCurrentYear
        echo "<p>Population: {$this->population}</p>";
        echo "<p>Houses: {$this->houses}</p>";
        echo "<p>Homeless: {$this->homeless}</p>";
        echo "<p>Happiness: {$this->happiness}%</p>";
        echo "<p>Balance: {$this->balance}</p>";
        echo "<p>Tax Level: {$taxLevels[$this->taxLevel - 1]}</p>"; // Show the Tax Level
        echo "<p>Cost to Build One House: {$this->houseCost}</p>";
        echo "<p>Tax Collected Per House: {$this->taxPerHouse}</p>";
    }

    public function getCurrentMonth()
    {
        return $this->currentMonth;
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function clearNotifications()
    {
        $this->notifications = [];
    }

    public function addCitizen($id, $name)
    {
        $this->citizens[$id] = ['name' => $name, 'employed' => false];
    }

    public function getCitizenInfo($id)
    {
        return isset($this->citizens[$id]) ? $this->citizens[$id] : null;
    }

    private function updatePopulation()
    {
        $this->population = max(100, $this->houses * 6);
        $this->homeless = max(0, $this->population - $this->houses * 6);

        for ($i = 1; $i <= $this->population; $i++) {
            if (!isset($this->citizens[$i])) {
                $randomName = $this->generateRandomName();
                $this->addCitizen($i, $randomName);
            }
        }
    }

    private function generateRandomName()
    {
        $firstNames = ['John', 'Jane', 'Bob', 'Alice', 'Charlie'];
        $lastNames = ['Smith', 'Doe', 'Johnson', 'Williams', 'Taylor'];

        $randomFirstName = $firstNames[array_rand($firstNames)];
        $randomLastName = $lastNames[array_rand($lastNames)];

        return "$randomFirstName $randomLastName";
    }

    private function updateHappiness()
    {
        $taxImpact = $this->taxLevel * 10;

        if ($this->taxLevel == 3) {
            $this->happiness -= $taxImpact;
        }

        $this->happiness -= $this->homeless * 5;
        $this->happiness = min(100, max(0, $this->happiness));
    }

    private function collectTaxes()
    {
        $taxes = $this->houses * $this->taxPerHouse;
        $this->balance += $taxes;

        if ($this->taxLevel == 1) {
            $this->happiness += $taxes * 0.1;
        } elseif ($this->taxLevel == 3) {
            $this->happiness -= $taxes * 0.1;
        }

        $this->happiness = min(100, max(0, $this->happiness));
    }

    private function attractNewCitizens()
    {
        if ($this->homeless > 0 && $this->happiness > 70) {
            $newCitizens = min($this->homeless, rand(1, 5));
            $this->homeless -= $newCitizens * 6;

            for ($i = $this->population + 1; $i <= $this->population + $newCitizens; $i++) {
                $this->addCitizen($i, "New Citizen {$i}");
            }

            $this->addNotification("Attracted {$newCitizens} new citizens!", 'info');
        }
    }

    private function updateCurrentMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
        } else {
            $this->currentMonth++;
        }
    }

    private function lowerTaxes()
    {
        $this->taxLevel = max(1, $this->taxLevel - 1);
    }

    private function addNotification($message, $type = 'info')
    {
        $notification = ['message' => $message, 'type' => $type, 'timestamp' => time()];
        $this->notifications[] = $notification;

        // Increment notification count
        $_SESSION['notification_count'] = isset($_SESSION['notification_count']) ? ($_SESSION['notification_count'] + 1) : 1;

        // Store notifications and count in the session
        $_SESSION['notifications'][] = $notification;
        $_SESSION['notification_count'] = isset($_SESSION['notification_count']) ? $_SESSION['notification_count'] : 0;
    }

    private function displayNotifications()
    {
        foreach ($this->notifications as $notification) {
            $class = 'notification';
            if (isset($notification['type'])) {
                $class .= ' ' . $notification['type'];
            }
            echo "<div class=\"$class\">{$notification['message']}</div>";
        }

        $this->notifications = [];
    }

    public function displayCitizens($search = null)
    {
        echo "<h2>Citizens</h2>";

        foreach ($this->citizens as $citizenId => $citizen) {
            if ($search === null || stripos($citizen['name'], $search) !== false) {
                echo "<p>Citizen ID: $citizenId, Name: {$citizen['name']}, Employed: {$citizen['employed']}</p>";
            }
        }
    }

    private function triggerEvent($message, $type = 'info')
    {
        $this->addNotification($message, $type);
        // Additional actions related to the triggered event can be added here
    }

    private function triggerRandomEvent()
    {
        // Implement logic for triggering random events
        // For simplicity, it's triggered based on a random chance
        $randomChance = rand(1, 100);

        if ($randomChance <= 10) {
            $this->triggerEvent("Random event occurred!", 'event');
        }
    }

    private function evaluateEventCondition($condition)
    {
        // Implement condition evaluation logic based on city properties
        // For simplicity, returning true always triggers the event
        return true;
    }

    private function checkAndTriggerRandomEvent()
    {
        $randomChance = rand(1, 100);

        if ($randomChance <= 10) {
            $this->triggerEvent("Random event occurred!", 'event');
        }
    }
}

// Example: Schedule an event if the city's population exceeds 1000
$city->scheduleEvent('Festival', $city->getPopulation() > 1000, 'A festival is happening in the city!');

// Example: Schedule an event if the city's balance drops below 500
$city->scheduleEvent('Recession', $city->getBalance() < 500, 'The city is facing an economic recession!');

// Example: Schedule a yearly anniversary celebration event
$city->scheduleEvent('Anniversary', $city->getCurrentMonth() == 1, 'Happy city anniversary!');

$_SESSION['game_year'] = date('Y');

?>
