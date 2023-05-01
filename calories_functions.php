<?php



//add calories
funtion addCalories($conn, $username, $date, $calories_added)
{
    $query = "INSERT INTO Calories (username, date, calories_added) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $date, $calories_added);
    $result = $stmt->execute();

    return $result;
}
//update Calories
function updateCalories($conn, $username, $date, $calories_added)
{
    $query = "UPDATE Calories SET calories_added = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $calories_added, $username);
    $result = $stmt->execute();

    return $result;
}


?>