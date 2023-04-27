<?php

function createUsersTable($conn) {
    $createUserTable = "CREATE TABLE Users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(50) NOT NULL UNIQUE,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(30) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($createUserTable) === TRUE) {
        echo "Users table created successfully";
    }
    else {
        echo "Error creating table: " . $conn->error;
    }
    
    $conn->close();
}

?>