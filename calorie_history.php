<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/tracker.css" />
    <link rel="stylesheet" type="text/css" href="./css/calorie_history.css" />
    <link rel="stylesheet" type="text/css" href="./css/background.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <h1 id="title">Calorie Analytics</h1>
    <hr />

    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        date_default_timezone_set('America/Los_Angeles');
        $months = range(1, 12);
        $year = date('Y');
        $years = range($year - 5, $year);

        $current_month = isset($_POST['month']) ? $_POST['month'] : date('n');
        $current_day = isset($_POST['day']) ? $_POST['day'] : date('j');
        $current_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        ?>
        <h4 style="text-align: center;">Select a Start and End Date:</h4>
        <div id="dateForm">
            <form method="post">
                <label style="margin-right: 5px"><strong>Start: </strong> &emsp; Month: </label>
                <select name="month" id="monthSelect" style="margin-right: 5px">
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
        </div>

        <?php
        $months1 = range(1, 12);
        $year1 = date('Y');
        $years1 = range($year - 5, $year);

        $current_month1 = isset($_POST['month1']) ? $_POST['month1'] : date('n');
        $current_day1 = isset($_POST['day1']) ? $_POST['day1'] : date('j');
        $current_year1 = isset($_POST['year1']) ? $_POST['year1'] : date('Y');
        ?>
        <div id="dateForm2">
            <form method="post">
                <label style="margin-right: 10px"><strong>End: </strong>&emsp; Month:</label>
                <select name="month1" style="margin-right: 8px">
                    <?php foreach ($months1 as $month) { ?>
                        <option value="<?php echo $month; ?>" <?php if ($month == $current_month1) {
                               echo ' selected';
                           } ?>>
                            <?php echo date('m', mktime(0, 0, 0, $month, 1));
                            ?>
                        </option>
                    <?php } ?>
                </select>

                <label style="margin-right: 11px">Day: </label>
                <select name="day1" style="margin-right: 10px">
                    <?php for ($day = 1; $day <= 31; $day++) { ?>
                        <option value="<?php echo $day; ?>" <?php if ($day == $current_day1) {
                               echo ' selected';
                           } ?>>
                            <?php echo $day; ?>
                        </option>
                    <?php } ?>
                </select>

                <label style="margin-right: 11px">Year:</label>
                <select name="year1" style="margin-right: 8px">
                    <?php foreach ($years1 as $year) { ?>
                        <option value="<?php echo $year; ?>" <?php if ($year == $current_year1) {
                               echo ' selected';
                           } ?>>
                            <?php echo $year; ?>
                        </option>
                    <?php } ?>
                </select>
        </div>

        <button id="date" type="submit" name="get_date">Submit</button>
        </form>
        <canvas id="lineChart"></canvas>

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

            $current_date1 = $current_year1;
            $current_date1 .= "-";
            if ($current_month1 < 10) {
                $current_date1 .= "0";
                $current_date1 .= $current_month1;
            } else {
                $current_date1 .= $current_month1;
            }
            $current_date1 .= "-";

            if ($current_day1 < 10) {
                $current_date1 .= "0";
                $current_date1 .= $current_day1;
            } else {
                $current_date1 .= $current_day1;
            }


        } else {
            $current_date = date("Y-m-d");
            $current_date1 = date("Y-m-d");
        }

        $valid = TRUE;

        if ($current_year > $current_year1) {
            $valid = FALSE;
        } else if ($current_month > $current_month1 && $current_year >= $current_year1) {
            $valid = FALSE;
        } else if ($current_day > $current_day1 && $current_month >= $current_month1 && $current_year >= $current_year1) {
            $valid = FALSE;
        }

        if ($valid) {
            global $conn;
            include 'mysql_connector.php';
            include 'calories_functions.php';
            $val = getRangeCals($conn, $_COOKIE['username'], $current_date, $current_date1);

            $nested_arr = array();
            foreach ($val as $dates) {
                $total_cal = getTotalCals($conn, $_COOKIE['username'], $dates);
                array_push($nested_arr, array("y" => $total_cal, "label" => $dates));
            }

            ?>
            <script>
                var values = <?php echo json_encode($nested_arr); ?>;
                var dates = [];
                var cals = [];
                for (let i = 0; i < values.length; i++) {
                    cals.push(values[i].y);
                }
                for (let i = 0; i < values.length; i++) {
                    dates.push(values[i].label);
                }


                var ctx = document.getElementById('lineChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: 'Calories',
                            data: cals
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Total Calories by Date'
                            }
                        }
                    }
                });
            </script>
            <canvas id="lineChart"></canvas>

            <?php
        } else {
            echo '<p>';
            echo "Invalid date selection.";
            echo '</p>';
        }
    } else { ?>
        <h2 id="promptSignIn">
            Sign in to access our Calorie Analytics Feature!
        </h2>
        <form id="signInForm" method="post" action="login.php">
            <button id="signInButton" type="submit">Sign In</button>
        </form>
    <?php } ?>
</body>

</html>