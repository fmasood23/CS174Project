<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/tracker.css" />

</head>

<body>
    <div style="text-align: center">
        <?php include './nav.php'; ?>

        <h2>Add a Meal!</h2>

        <div id="mealItemContainer">
            <form class="mealItemForm" method="post" action="trackerAddMeal.php">
                <label name="mealType_name">Meal:</label>
                <select name="mealType">
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                    <option value="other">Other</option>
                </select>
                </br>
                <label for="foodItemLabel">Food:</label>
                <select name="foodItemSelect" id="foodItemSelect" required>
                    <?php
                    if (($handle = fopen("food_calories.csv", "r")) !== false) {
                        $header = fgetcsv($handle, 564, ",");
                        while (($data = fgetcsv($handle, 564, ",")) !== false) {
                            $selected = isset($_POST["foodItemSelect"]) && $_POST["foodItemSelect"] == $data[0] ? "selected" : "";
                            echo "<option value=\"" .
                                $data[0] .
                                "\" data-calories=\"" .
                                $data[2] .
                                "\" data-serving-unit=\"" .
                                $data[3] .
                                "\"" . $selected . ">" .
                                $data[0] .
                                "</option>";
                        }
                        fclose($handle);
                        if (isset($_POST["foodItemSelect"]) && isset($_POST["servingSize"])) {
                            $_POST["foodCals"] = 0;
                        }
                    } ?>
                </select>
                <label for="servingSizeLabel">Serving Size:</label>
                <input type="number" name="servingSize" id="servingSize" value="0" min="0" step="any" required>
                <label for="foodCalsLabel">Calories:</label>
                <input type="number" name="foodCals" id="foodCals" value="0" required readonly <?php if (
                    isset($_POST["foodCals"])
                ) {
                    echo "value=\"" . $_POST["foodCals"] . "\"";
                } ?>>

                <input type="submit" name="submit" value="Submit">

                <script>
                    const servingSizeInput1 = document.getElementById('servingSize');
                    const calorieAmountInput1 = document.getElementById('foodCals');
                    const mealNameSelect1 = document.getElementById('foodItemSelect');

                    mealNameSelect1.addEventListener('change', function () {
                        calorieAmountInput1.value = 0;
                    });

                    servingSizeInput1.addEventListener('input', function () {
                        const selectedOption = mealNameSelect1.options[mealNameSelect1.selectedIndex];
                        const caloriePerServing = parseFloat(selectedOption.getAttribute('data-calories'));
                        const servingUnit = selectedOption.getAttribute('data-serving-unit');
                        const servingSize = parseFloat(servingSizeInput1.value);
                        const calorieAmount = Math.round(caloriePerServing * servingSize);

                        calorieAmountInput1.value = calorieAmount;
                    });
                </script>
            </form>

            <?php
            $foodCals = 0;
            if (isset($_POST["foodItemSelect"]) && isset($_POST["servingSize"])) {
                $foodItemSelect = $_POST["foodItemSelect"];
                $servingSize = $_POST["servingSize"];
                if (($handle = fopen("food_calories.csv", "r")) !== false) {
                    $header = fgetcsv($handle, 564, ",");
                    while (($data = fgetcsv($handle, 564, ",")) !== false) {
                        if ($data[0] == $foodItemSelect) {
                            $foodCals = ((float) $data[2] * (float) $servingSize) / (float) $data[1];
                            break;
                        }
                    }
                    fclose($handle);
                }

                if (isset($_POST["submit"])) {
                    include 'mysql_connector.php';
                    include 'calories_functions.php';

                    global $conn;

                    $user_cal = $_COOKIE['username'];
                    $date_cal = date("Y-m-d");
                    $selectOption = $_POST['mealType'];

                    $result = addCalories($conn, $user_cal, $date_cal, $foodCals, $selectOption, $foodItemSelect);

                    if ($result) {
                        echo '<meta http-equiv="refresh" content="0; URL=trackerAddMeal.php">';

                    }
                }
            }
            ?>
        </div>

        <br />
        <h2>Delete a Meal!</h2>

        <form class="deleteForm" method="post" action="trackerAddMeal.php">
            <label name="meal_name">Select a Meal:</label>
            <select name="meal">
                <?php

                include 'mysql_connector.php';
                include 'calories_functions.php';

                global $conn;
                getAllMeals($conn, $_COOKIE['username']);
                ?>
            </select>
            <input type="submit" name="delete" value="Delete">
        </form>
        <?php

        if (isset($_POST["delete"])) {

            $user_meal = $_COOKIE['username'];
            $selected = $_POST['meal'];

            $result = deleteMeal($conn, $user_meal, $selected);

            if ($result) {
                echo '<meta http-equiv="refresh" content="0; URL=trackerAddMeal.php">';

            }
        }
        ?>
    </div>

</body>

</html>