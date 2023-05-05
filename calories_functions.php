<?php


//add calories
function addCalories($conn, $username, $date, $calories_added, $meal_type, $meal_name)
{
    $query = "INSERT INTO calories (username, date, calories_added, meal_type, meal_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $username, $date, $calories_added, $meal_type, $meal_name);
    $result = $stmt->execute();

    return $result;
}
//update Calories
function updateCalories($conn, $username, $date, $calories_added)
{
    $query = "UPDATE calories SET calories_added = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $calories_added, $username);
    $result = $stmt->execute();

    return $result;
}

function getAllMeals($conn, $username)
{

    $query = "SELECT meal_name FROM calories WHERE username='";
    $query .= $username;
    $query .= "';";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $categories = array($row["meal_name"]);
        $arrlength = count($categories);

        for ($x = 0; $x < $arrlength; $x++) {
            echo "<option selected='selected'>";
            echo $categories[$x];
            echo "</option>";
        }
    }
}

function deleteMeal($conn, $username, $meal)
{
    $query = "DELETE FROM calories WHERE username = ? AND meal_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $meal);
    $result = $stmt->execute();

    return $result;
}

function getTotalCals($conn, $username, $date)
{
    $count = 0;
    $query = "SELECT calories_added FROM calories WHERE username = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $count += (int) $row["calories_added"];
        }
        return $count;
    } else {
        return 0;
    }

}
?>