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
        <h3 style="text-align: center;">Select a Date:</h3>
        <form id="dateForm" method="post">
            <label style="margin-right: 5px">Month:</label>
            <select name="month" id="monthSelect" style="margin-right: 5px">
                <?php foreach ($months as $month) { ?>
                    <option value="<?php echo $month; ?>" <?php if ($month == $current_month) {
                           echo ' selected';
                       } ?>>
                        <?php echo date('m', mktime(0, 0, 0, $month, 1)); ?>
                    </option>
                <?php } ?>
            </select>

            <label style="margin-right: 5px">Day:</label>
            <select name="day" id="daySelect" style="margin-right: 5px">
                <?php
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
                for ($day = 1; $day <= $days_in_month; $day++) { ?>
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

            <button type="submit" name="get_date">Submit</button>
        </form>

        <script>
            const monthSelect = document.getElementById('monthSelect');
            const daySelect = document.getElementById('daySelect');
            const daysInMonth = [
                31, // January
                28, // February
                31, // March
                30, // April
                31, // May
                30, // June
                31, // July
                31, // August
                30, // September
                31, // October
                30, // November
                31  // December
            ];

            monthSelect.addEventListener('change', function () {
                const selectedMonth = parseInt(monthSelect.value);
                const days = daysInMonth[selectedMonth - 1];

                daySelect.innerHTML = '';

                for (let day = 1; day <= days; day++) {
                    const option = document.createElement('option');
                    option.value = day;
                    option.textContent = day;
                    if (day === <?php echo $current_day; ?>) {
                option.selected = true;
            }
            daySelect.appendChild(option);
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                        });
        </script>

        <?php
        if (isset($_POST["get_date"])) {
            $current_date = $current_year;
            $current_date .= "-";
            if ($current_month < 10) {
                $current_date .= "0";
                $current_date .= $current_month;
            } else {
                $current_date .= $current_month;
            }
            $current_date .= "-";
            if ($current_day < 10) {
                $current_date .= "0";
                $current_date .= $current_day;
            } else {
                $current_date .= $current_day;
            }
        } else {
            $current_date = date("Y-m-d");
        }
        ?>

        <br />
        <div id="calorieCircle">
            <p style="text-align: center; font-size: 20px"><strong>Calories Intake:</strong>
                <?php
                $val = "";
                global $conn;
                include 'mysql_connector.php';
                include 'calories_functions.php';
                $val .= getTotalCals($conn, $_COOKIE['username'], $current_date);
                $val .= " cals";
                echo $val; ?>
            </p>
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
            <p style="text-align: center; font-size: 20px"><strong>Remaining Intake:</strong>
                <?php
                global $conn;
                include 'mysql_connector.php';
                $total = getTotalCals($conn, $_COOKIE['username'], $current_date);
                $goal = getGoal($conn, $_COOKIE['username']);

                $difference = (int) $goal - (int) $total;

                if ($difference > 0) {
                    $difference .= " cals";
                    echo $difference;
                } else {
                    echo "Completed <br> goal!";
                } ?>
            </p>
        </div>

        <br />
        <div>
            <div id="mealItemContainer">
                <hr />
                <h3 id="mealUpdateTitle">Update Meals!</h3>
                <p id="mealUpdateDescription">Add a new meal item using the form below!
                </p>
                <form class="mealItemForm" method="post">
                    <label name="mealType_name">Meal:</label>
                    <select class="equalSize" name="mealType">
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="other">Other</option>
                    </select>
                    </br>
                    <label for="foodItemLabel">Food:</label>
                    <select class="equalSize" name="foodItemSelect" id="foodItemSelect" required>
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
                    </br>
                    <label for="servingSizeLabel">Serving Size:</label>
                    <input type="number" name="servingSize" id="servingSize" class="equalSize" value="0" min="0" step="any"
                        required>
                    </br>
                    <label for="foodCalsLabel">Calories:</label>
                    <input type="number" name="foodCals" id="foodCals" class="equalSize" value="0" required readonly <?php if (
                        isset($_POST["foodCals"])
                    ) {
                        echo "value=\"" . $_POST["foodCals"] . "\"";
                    } ?>>
                    </br>
                    <input id="submitMeal" type="submit" name="submit" value="Submit">

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

                <div id="addContainer">
                    <hr />
                    <p id="addMealDescription">Can't find an item? Add it to the dataset using the form below! </p>
                    <button id="customMealBtn" type="submit">Add Custom Food</button>
                </div>

                <script>
                    const customMealBtn = document.getElementById('customMealBtn');
                    const mealItemContainer = document.getElementById('mealItemContainer');
                    let formIndex = 0;

                    customMealBtn.addEventListener('click', function () {
                        const newForm = document.createElement('form');
                        newForm.className = 'customItemForm';
                        newForm.method = 'post';

                        const foodItemLabel = document.createElement("label");
                        foodItemLabel.className = "foodItemLabel";
                        foodItemLabel.textContent = "Food:";
                        foodItemLabel.style.marginRight = "6px";
                        newForm.appendChild(foodItemLabel);

                        const foodItemInput = document.createElement("input");
                        foodItemInput.className = "foodItem";
                        foodItemInput.required = true;
                        foodItemInput.style.width = "283px";
                        foodItemInput.style.marginRight = "5px";
                        newForm.appendChild(foodItemInput);

                        const newLine = document.createElement('br');
                        newForm.appendChild(newLine);

                        const servingSizeLabel = document.createElement("label");
                        servingSizeLabel.className = "servingSizeLabel";
                        servingSizeLabel.textContent = "Serving Size:";
                        servingSizeLabel.style.marginRight = "6px";
                        newForm.appendChild(servingSizeLabel);

                        const servingSizeInput = document.createElement("input");
                        servingSizeInput.type = "number";
                        servingSizeInput.style.width = "283px";
                        servingSizeInput.value = "1";
                        servingSizeInput.step = "any";
                        servingSizeInput.required = true;
                        servingSizeInput.readOnly = true;
                        servingSizeInput.style.marginRight = "6px";
                        newForm.appendChild(servingSizeInput);

                        const newLine1 = document.createElement('br');
                        newForm.appendChild(newLine1);

                        const foodCalsLabel = document.createElement("label");
                        foodCalsLabel.className = "foodCalsLabel";
                        foodCalsLabel.textContent = "Calories:";
                        foodCalsLabel.style.marginRight = "6px";
                        newForm.appendChild(foodCalsLabel);

                        const calorieAmountInput = document.createElement("input");
                        calorieAmountInput.type = "number";
                        calorieAmountInput.value = "0";
                        calorieAmountInput.min = "0";
                        calorieAmountInput.step = "any";
                        calorieAmountInput.required = true;
                        calorieAmountInput.style.width = "283px";
                        calorieAmountInput.style.marginRight = "6px";
                        newForm.appendChild(calorieAmountInput);

                        const newLine2 = document.createElement('br');
                        newForm.appendChild(newLine2);

                        const submitBtn = document.createElement('input');
                        submitBtn.type = 'submit';
                        submitBtn.name = 'submit';
                        submitBtn.value = 'Submit';
                        submitBtn.style.backgroundColor = "#BFD1DF";
                        submitBtn.style.borderColor = "#BFD1DF";
                        submitBtn.style.width = "100px";
                        submitBtn.style.height = "25px";
                        submitBtn.style.fontSize = "15px";
                        submitBtn.style.borderRadius = "8px";
                        submitBtn.style.marginTop = "10px";

                        submitBtn.addEventListener("mouseenter", function (event) {
                            event.target.style.backgroundColor = "#5B9EA6";
                            event.target.style.borderColor = "#5B9EA6";
                        }, false);

                        submitBtn.addEventListener("mouseleave", function (event) {
                            event.target.style.backgroundColor = "#BFD1DF";
                            event.target.style.borderColor = "#BFD1DF";
                        }, false);


                        submitBtn.addEventListener('click', function (event) {
                            event.preventDefault();

                            const foodItemValue = foodItemInput.value;
                            const servingSizeValue = servingSizeInput.value;
                            const calorieAmountValue = calorieAmountInput.value;

                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'write_to_csv.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            const data = `foodItem=${encodeURIComponent(foodItemValue)}&servingSize=${encodeURIComponent(servingSizeValue)}&calorieAmount=${encodeURIComponent(calorieAmountValue)}`;

                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                    console.log(xhr.responseText);
                                    location.reload();
                                }
                            };
                            xhr.send(data);
                        });

                        newForm.appendChild(submitBtn);
                        mealItemContainer.appendChild(newForm);
                    });
                </script>

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

                        global $conn;

                        $user_cal = $_COOKIE['username'];
                        $date_cal = date("Y-m-d");
                        $selectOption = $_POST['mealType'];

                        $result = addCalories($conn, $user_cal, $date_cal, $foodCals, $selectOption, $foodItemSelect);

                        if ($result) {
                            echo '<meta http-equiv="refresh" content="0; URL=tracker.php">';
                        }
                    }
                }
                ?>
            </div>
            <br />
            <div id="addMeal"></div>
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