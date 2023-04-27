<?php
// find user credentials
function checkLoginCreds($conn, $username, $password) {
    $query = "SELECT * FROM Users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if (is_null($result)) {
        return FALSE;
    }
    return TRUE;
}

// create user account
function createUserAccount($conn, $email, $username, $password) {
    $query = "INSERT INTO Users (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $username, $password);
    $result = $stmt->execute();
    
    return $result;
}

?>