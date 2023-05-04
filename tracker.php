<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/tracker.css" />
</head>

<body>
    <?php include './nav.php'; ?>

    <h1 id="test1">Calorie Tracker</h1>
    <hr />

    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        $months = range(1, 12);
        $year = date('Y');
        $years = range($year - 5, $year);

        $current_month = isset($_POST['month']) ? $_POST['month'] : date('n');
        $current_day = isset($_POST['day']) ? $_POST['day'] : date('j');
        $current_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        ?>
        <h4 style="text-align: center;">Date:</h4>
        <form id="dateForm" method="post">
            <label style="margin-right: 5px">Month:</label>
            <select name="month" style="margin-right: 5px">
                <?php foreach ($months as $month) { ?>
                    <option value="<?php echo $month; ?>" <?php if ($month == $current_month) {
                           echo ' selected';
                       } ?>>
                        <?php echo date('m', mktime(0, 0, 0, $month, 1));
                        ?>
                    </option>
                <?php } ?>
            </select>

            <label style="margin-right: 5px">Day:</label>
            <select name="day" style="margin-right: 5px">
                <?php for ($day = 1; $day <= 31; $day++) { ?>
                    <option value="<?php echo $day; ?>" <?php if ($day == $current_day) {
                           echo ' selected';
                       } ?>>
                        <?php echo $day; ?>
                    </option>
                <?php } ?>
            </select>

            <label style="margin-right: 5px">Year:</label>
            <select name="year" style="margin-right: 5px">
                <?php foreach ($years as $year) { ?>
                    <option value="<?php echo $year; ?>" <?php if ($year == $current_year) {
                           echo ' selected';
                       } ?>>
                        <?php echo $year; ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Submit</button>
        </form>

        <br />
        <div id="calorieCircle">
            <p style="text-align: center; font-size: 20px"><strong>Calories Today:</strong> 1600 cals</p>
            <p style="text-align: center; font-size: 20px"><strong>Goal:</strong>
                <?php
                $val = "";
                global $conn;
                include 'mysql_connector.php';
                include 'goal_functions.php';

                $val .= getGoal($conn, $_COOKIE['username']);
                $val .= " cals";
                echo $val; ?>
            </p>
            <p style="text-align: center; font-size: 20px"><strong>Remaining Intake:</strong> 400 cals</p>
        </div>

        <br />
        <div style="text-align: center;">
            <div id="mealItemContainer">
                <form class="mealItemForm" method="post">
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
                            echo '<p>works</p>';
                        }
                    }
                }
                ?>
            </div>
            <br />
            <div id="addMeal"></div>
        </div>

        <br />
        <div id="mealDropdown">
            <p>Meal: Breakfast</p>
            <p>Calories: 1000</p>
        </div>
        <br />
    <?php } else { ?>
        <h2 id="promptSignIn">
            Sign in to access our Calorie Tracker Feature!
        </h2>
        <form id="signInForm" method="post" action="login.php">
            <button id="signInButton" type="submit">Sign In</button>
        </form>
    <?php } ?>
</body>

</html>