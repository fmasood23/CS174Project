<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$foodItem = str_replace('"', '', $_POST['foodItem']);
$servingSize = $_POST['servingSize'];
$calorieAmount = $_POST['calorieAmount'];

$data = array($foodItem, $servingSize, $calorieAmount);
$file = fopen('food_calories.csv', 'a');
flock($file, LOCK_EX);
fputcsv($file, $data);
flock($file, LOCK_UN);
fclose($file);
?>