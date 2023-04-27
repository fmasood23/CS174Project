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

// Create user table
$createUserTable = "CREATE TABLE Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($createUserTable) === TRUE) {
    echo "Users table created successfully";
}
else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>