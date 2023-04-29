<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/home.css" />
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
</head>

<body>
    <?php include 'nav.php'; ?>
    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        ?>
        <h2>Today's Calorie Intake</h2>
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

        <form method="post" action="tracker.php">
            <button class="button" type="submit">Update Calorie Intake</button>
        </form>

    <?php } else { ?>
        <div id="screen">
            <div id="text_button_wrapper">
                <p id="test">Welcome to [APP NAME]! <br> An efficient way to track and monitor calorie intake.</p>
                <form method="post">
                    <input id="login_route" type="submit" name="login" value="Get Started!" />
                </form>
            </div>
            <img
                src="https://png.pngtree.com/png-clipart/20221021/original/pngtree-counting-calories-isolated-cartoon-vector-illustrations-png-image_8710800.png" />

        </div>
        <?php
        if (isset($_POST['login'])) {
            header("location: login.php");
        }
        ?>
    <?php }
    ?>

</body>

</html>