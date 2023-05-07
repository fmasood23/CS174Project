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

function getIntakeFromDate($conn, $username, $date) {
    $query = "
        SELECT * FROM calories WHERE username = ? AND date = ? 
        ORDER BY CASE meal_type 
        WHEN 'breakfast' THEN 1 
        WHEN 'lunch' THEN 2 
        WHEN 'dinner' THEN 3 
        ELSE 4 END
    ";
            
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function deleteMeal($conn, $calories_id)
{
    $query = "DELETE FROM calories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $calories_id);
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


function getRangeCals($conn, $username, $date_start, $date_end)
{
    $count = 0;
    $query = "SELECT DISTINCT date FROM calories WHERE username = ? AND date >= ? AND date <= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $date_start, $date_end);
    $stmt->execute();
    $result = $stmt->get_result();

    $arr = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, $row["date"]);
        }
        return $arr;
    } else {
        return $arr;
    }

}
?>