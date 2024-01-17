<?php
require_once 'City.php';

$config = [
    'initial_population' => 1000,
    'initial_houses' => 0,
    'initial_homeless' => 50,
    'initial_happiness' => 75,
    'initial_tax_level' => 2,
    'initial_balance' => 10000,
    'house_cost' => 150,
    'tax_per_house' => 30,
    // ... (additional configuration)
];

$city = new City($config);
