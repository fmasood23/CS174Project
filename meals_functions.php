<?php
//add name
//add serving size
//add calories

function addMealInfo($conn, $name, $serving_size, $calories)
{
    $query = "INSERT INTO Meals (name, serving_size, calories) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $serving_size, $calories);
    $result = $stmt->execute();
    return $result;
}





?>