<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/tracker.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script style="text-align: center;">
        $(document).ready(function () {
            $('#addMealBtn').click(function () {
                let $addMeal = $('#addMeal');
                if ($addMeal.html().trim() === '') {
                    $.ajax({
                        url: './trackerAddMeal.php',
                        type: 'GET',
                        success: function (result) {
                            $addMeal.html(result);
                        },
                    });
                }
            });
        });
    </script>
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

        $current_month = date('n');
        $current_day = date('j');
        $current_year = date('Y');
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
            <button id="addMealBtn" style="font-size: 20px;">Add meal to calorie intake</button>
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