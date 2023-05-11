<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/profile.css" />
    <link rel="stylesheet" type="text/css" href="./css/background.css" />
</head>

<body>
    <?php include 'nav.php'; ?>
    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        ?>

        <div id="profile">
            <img src="https://cdn-icons-png.flaticon.com/512/2948/2948035.png" />
            <p id="username">
                <?php echo $_COOKIE['username'] ?>
            </p>
            <div id="goal_wrapper">
                <p id="dailyGoal"><strong>Daily Goal: </strong>
                    <?php
                    $val = "";
                    global $conn;
                    include 'mysql_connector.php';
                    include 'goal_functions.php';
                    $val .= getGoal($conn, $_COOKIE['username']);
                    $val .= " calories";
                    echo $val; ?>
                </p>
            </div>

            <form method="post" action="edit_profile.php">
                <input class="button" type="submit" name="change_profile" value="Edit Profile" />
            </form>

            <form method="post" action="calorie_history.php">
                <input class="button" type="submit" name="view_history" value="Calorie Analytics" />
            </form>

            <form method="post">
                <input class="button" type="submit" name="signout" value="Sign Out" />
            </form>
        </div>
        <br>

        <?php
        if (isset($_POST['signout'])) {
            setcookie("logged_in", time() - 3600);
            SETCOOKIE('logged_in', 'false', 0, '/');

            unset($_COOKIE['username']);
            setcookie('username', '', time() - 3600, '/');
            header("location: login.php");
        }
        ?>
    <?php } else { ?>
        <h2 id="promptSignIn">
            Sign in to access our Profile Page!
        </h2>
        <form id="signInForm" method="post" action="login.php">
            <button id="signIn" type="submit">Sign In</button>
        </form>
    <?php }
    ?>

</body>

</html>