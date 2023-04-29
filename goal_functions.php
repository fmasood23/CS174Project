<?php

//sets default goal of 0
function defaultGoal($conn, $username, $goal)
{
    $query = "INSERT INTO Goal VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $goal);
    $result = $stmt->execute();

    return $result;
}

//update goal value
function updateGoal($conn, $username, $goal)
{
    $query = "UPDATE Goal SET goal = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $goal, $username);
    $result = $stmt->execute();

    return $result;
}

//query to get goal amount
function getGoal($conn, $username)
{
    $sql = "SELECT goal FROM Goal WHERE username = '";
    $sql .= $username;
    $sql .= "';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row["goal"];
        }
    } else {
        return FALSE;
    }
}

?>