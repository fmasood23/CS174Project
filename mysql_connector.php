<?php
$servername = "cos-cs106.science.sjsu.edu";
$username = "group2user";
$password = "WhLK8##7JQ";
$dbname = "group2db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>