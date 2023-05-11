<!DOCTYPE html>
<html>

<head>
    <title>Calories Intake Details</title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/intake_details.css" />
    <link rel="stylesheet" type="text/css" href="./css/background.css" />
</head>

<body>
    <?php include 'nav.php'; ?>
    <h1 id="titlename" style="text-align: center;">Calorie Intake Details</h1>
    <hr />
</body>

</html>

<?php
if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
    date_default_timezone_set('America/Los_Angeles');
    include 'calories_functions.php';
    include 'mysql_connector.php';

    $conn;

    $months = range(1, 12);
    $year = date('Y');
    $years = range($year - 5, $year);

    $current_month = isset($_POST['month']) ? $_POST['month'] : date('m');
    $current_day = isset($_POST['day']) ? $_POST['day'] : date('d');
    $current_year = isset($_POST['year']) ? $_POST['year'] : date('Y');

    $current_date = $current_year . '-' . $current_month . '-' . $current_day;
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

        <button class="btnStyle" type="submit" name="get_date">Submit</button>
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
    $todayIntake = getIntakeFromDate($conn, $_COOKIE['username'], $current_date);
    $meal_type = '';
    while ($row = $todayIntake->fetch_assoc()) {
        if ($meal_type != $row['meal_type']) {
            if ($meal_type != '') {
                echo '</tbody></table><br>';
            }
            $meal_type = $row['meal_type'];
            echo '
            <div id="meal_container">
                <h2>' . $meal_type . '</h2>
                <table id="meal_table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Calories</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        }

        echo '
            <tr>
                <td>' . $row["meal_name"] . '</td>
                <td>' . $row["calories_added"] . '</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="meal_id" value=' . $row["id"] . '>
                        <input type="submit" class="btnStyle" name="delete_meal" value="Delete">
                    </form>
                </td>
            </tr>
        </div>';
    }

    if ($meal_type != '') {
        echo '</tbody></table><br>';
    }

    if (isset($_POST['delete_meal'])) {
        $meal_id = $_POST['meal_id'];
        $result = deleteMeal($conn, $meal_id);
        if ($result) {
            echo '<meta http-equiv="refresh" content="0; URL=calorie_intake_details.php">';
        }
    }
} else { ?>
    <h2 id="promptSignIn">
        Sign in to access our Previous Calorie Intake Feature!
    </h2>
    <form id="signInForm" method="post" action="login.php">
        <button id="signInButton" type="submit">Sign In</button>
    </form>
<?php } ?>