<?php


//add calories
function addCalories($conn, $username, $date, $calories_added, $meal_type)
{
    $query = "INSERT INTO calories (username, date, calories_added, meal_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $date, $calories_added, $meal_type);
    $result = $stmt->execute();

    return $result;
}
//update Calories
function updateCalories($conn, $username, $date, $calories_added)
{
    $query = "UPDATE calories SET calories_added = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $calories_added, $username);
    $result = $stmt->execute();

    return $result;
}


?>