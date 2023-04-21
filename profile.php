<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/profile.css" />
</head>

<body>
    <?php include 'nav.php'; ?>
    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        ?>
        <div id="profile">
            <img src="https://cdn-icons-png.flaticon.com/512/2948/2948035.png" />
            <p id="username">User</p>
            <div id="goal_wrapper">
                <p id="dailyGoal">Daily Goal: </p>
                <form method="post">
                    <input id="change" type="submit" name="changeGoal" value="Change" />
                </form>
            </div>
            <form method="post">
                <input class="button" type="submit" name="changepass" value="Change Password" />
            </form>
            <form method="post">
                <input class="button" type="submit" name="signout" value="Sign Out" />
            </form>
        </div>

        <?php
        if (isset($_POST['signout'])) {
            SETCOOKIE("logged_in", '', 0, "/proj");
            SETCOOKIE('logged_in', 'false', 0, '/proj');
            header("location: login.php");
        }
        ?>
    <?php } else { ?>
        <h2 id="promptSignIn">
            Sign in to access our Calorie Tracker Feature!
        </h2>
        <form id="signInForm" method="post" action="login.php">
            <button class="button" type="submit">Sign In</button>
        </form>
    <?php }
    ?>

</body>

</html>