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
        <?php
        include 'mysql_connector.php';
        include 'user_functions.php';
        ?>
        <div id="profile">
            <img src="https://cdn-icons-png.flaticon.com/512/2948/2948035.png" />
            <p id="username">
                <?php echo $_COOKIE['username'] ?>
            </p>
            <div id="goal_wrapper">
                <p id="dailyGoal">Daily Goal: </p>
                <form method="post">
                    <input id="change" type="submit" name="changeGoal" value="Change" />
                </form>
            </div>

            <form method="post">
                <input class="button" type="submit" name="change_pass" value="Change Password" />
            </form>

            <?php
            if (isset($_POST['change_pass'])) {
                ?>
                <form action="profile.php" method="post">
                    <div id="change_pass_form">

                        <label for="password">Enter a New Password: </label>
                        <input type="password" placeholder="Enter New Password" name="new_pass" required>

                        <button type="submit" name="update">Change Password</button>
                    </div>

                    <?php
                    global $conn;
                    if (isset($_POST['update'])) {
                        $get_user = $_COOKIE['username'];
                        $get_pass = $_POST['new_pass'];

                        $updatePass = updatePassword($conn, $get_user, $get_pass);
                        if ($updatePass === TRUE) {
                            header("location: profile.php");
                        }
                    } ?>
                <?php } ?>

                <form method="post">
                    <input class="button" type="submit" name="signout" value="Sign Out" />
                </form>
        </div>

        <?php
        if (isset($_POST['signout'])) {
            SETCOOKIE("logged_in", '', 0, "/proj");
            SETCOOKIE('logged_in', 'false', 0, '/proj');

            unset($_COOKIE['username']);
            setcookie('username', '', time() - 3600, '/');
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