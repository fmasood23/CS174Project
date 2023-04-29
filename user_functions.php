<?php
// find user credentials
function checkLoginCreds($conn, $username, $password)
{
    $query = "SELECT * FROM Users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) < 1) {
        return FALSE;
    }
    return TRUE;
}

// create user account
function createUserAccount($conn, $email, $username, $password)
{
    $query = "INSERT INTO Users (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $username, $password);
    $result = $stmt->execute();

    return $result;
}

function updatePassword($conn, $username, $password)
{
    $query = "UPDATE Users set password=? WHERE username=?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $password, $username);
    $result = $stmt->execute();

    return $result;
}

?>