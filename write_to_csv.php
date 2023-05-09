<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$foodItem = str_replace('"', '', $_POST['foodItem']);
$servingSize = $_POST['servingSize'];
$calorieAmount = $_POST['calorieAmount'];

# check if already in CSV
$csvArrayMap = array_map('str_getcsv', file('food_calories.csv'));
foreach ($csvArrayMap as $row) {
    if ($row[0] === $foodItem) {
        exit();
    }
}

$data = array($foodItem, $servingSize, $calorieAmount);
$file = fopen('food_calories.csv', 'a');
flock($file, LOCK_EX);
fputcsv($file, $data);
flock($file, LOCK_UN);
fclose($file);
?>