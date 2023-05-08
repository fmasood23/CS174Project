<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/profile.css" />

</head>

<body>
    <?php include 'nav.php' 
    include 'cookies.php';
    ?>

    <?php
    if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
        ?>

        <div id="profile">
            <img src="https://cdn-icons-png.flaticon.com/512/2948/2948035.png" />
            <p id="username">
                <?php echo $_COOKIE['username'] ?>
            </p>
        </div>

        <div id="change_pass_form">
            <h2>Change Password</h2>

            <form action="edit_profile.php" method="post">

                <label for="password">Enter a New Password: </label>
                <input type="password" placeholder="Enter New Password" name="new_pass" required>

                <button type="submit" name="update">Change Password</button>

            </form>

            <?php
            global $conn;
            include 'mysql_connector.php';
            include 'user_functions.php';

            if (isset($_POST['update'])) {
                $get_user = $_COOKIE['username'];
                $get_pass = $_POST['new_pass'];

                $updatePass = updatePassword($conn, $get_user, $get_pass);
                if ($updatePass) {
                    header("location: profile.php");
                }
            } ?>

        </div>

        <div id="change_goal_form">
            <h2>Change Goal</h2>

            <form action="edit_profile.php" method="post">

                <label for="password">Enter a New Goal: </label>
                <input type="number" placeholder="Enter New Goal" name="new_goal" required>

                <button type="submit" name="update_goal">Change Goal</button>

            </form>

            <?php
            global $conn;
            include 'mysql_connector.php';
            include 'goal_functions.php';

            if (isset($_POST['update_goal'])) {
                $get_user = $_COOKIE['username'];
                $get_goal = $_POST['new_goal'];

                $updateGoal = updateGoal($conn, $get_user, $get_goal);
                if ($updateGoal) {
                    header("location: profile.php");
                }
            } ?>

        </div>

        <form method="post">
            <input class="button" type="submit" name="back" value="Back" />
        </form>
        </div>

        <?php
        if (isset($_POST['back'])) {
            header("location: profile.php");
        }
        ?>

        <form method="post">
            <input class="button" type="submit" name="sign_out" value="Sign Out" />
        </form>
        </div>

        <?php
        if (isset($_POST['sign_out'])) {
            SETCOOKIE("logged_in", '', time() - 3600, "/");
            SETCOOKIE('logged_in', 'false', 0, '/');

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