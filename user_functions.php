<?php
// find user credentials
function checkLoginCreds($conn, $username, $password)
{
    $query = "SELECT * FROM Users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    return mysqli_num_rows($result) == 1;
}

// create user account
function createUserAccount($conn, $email, $username, $password)
{
    $query = "INSERT INTO Users (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $username, $password);

    try {
        $result = $stmt->execute();
    } catch (mysqli_sql_exception $error) {
        if ($error->getCode() == 1062) {
            return FALSE;
        }
        throw $error;
    }

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