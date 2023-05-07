<?php
// find user credentials
function checkLoginCreds($conn, $username, $password)
{
    $query = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $row_count = mysqli_num_rows($result);

    if ($row_count < 1) {
        return "User doesn't exist.";
    } 
    else {
        $row = $result->fetch_assoc();

        return $password == $row["password"] ? "Login successful." : "Incorrect password.";
    }
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