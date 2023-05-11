<?php
$errorLogin = "";
$errorSignUp = "";
if (!empty($_POST)) {
    include 'mysql_connector.php';
    include 'user_functions.php';
    include 'goal_functions.php';

    global $conn;
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (isset($_POST["login_button"])) {
        $userExists = checkLoginCreds($conn, $username, $password);
        if ($userExists === "Login successful.") {
            $status = setcookie("logged_in", "true");
            $status = setcookie("username", $username);
            header("location: profile.php");
        } else {
            $errorLogin = $userExists;
        }
    } elseif (isset($_POST["signup_button"])) {
        $email = $_POST["email"];
        $accountCreationResult = createUserAccount($conn, $email, $username, $password);

        if ($accountCreationResult === FALSE) {
            $errorSignUp = "Username already exists.";
        } else {
            $setGoal = defaultGoal($conn, $username, 0);

            if ($setGoal === TRUE) {
                $status = setcookie("logged_in", "true");
                $status = setcookie("username", $username);
                header("location: profile.php");
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login or Sign Up</title>
    <link rel="stylesheet" type="text/css" href="./css/login.css" />
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
    <link rel="stylesheet" type="text/css" href="./css/background.css" />
</head>

<body>
    <?php include 'nav.php'; ?>
    <div id="screen">
        <div id="border">
            <h3 id="title">Login or Sign Up!</h3>
            <div id="login_wrapper">
                <p class="smaller_title">Sign In</p>
                <form action="login.php" method="post">
                    <div id="login_form">
                        <label for="username">Username: </label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="password">Password: </label>
                        <input type="password" placeholder="Enter Password" name="password" required>

                        <button class="button" type="submit" name="login_button">Login</button>
                        <?php if (!empty($errorLogin)) { ?>
                            <p id="error_message">
                                <?php echo $errorLogin; ?>
                            </p>
                        <?php } ?>
                    </div>
                </form>
            </div>

            <div class="vertical"></div>
            <div id="create_acc_wrapper">
                <p class="smaller_title">Create Account</p>
                <form action="login.php" method="post">
                    <div id="create_acc_form">
                        <label for="email">Enter your email: </label>
                        <input type="email" placeholder="Enter Email" name="email" required>

                        <label for="username">Create a Username: </label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="password">Create a Password: </label>
                        <input type="password" placeholder="Enter Password" name="password" required>

                        <button class="button" type="submit" name="signup_button">Create Account</button>
                        <?php if (!empty($errorSignUp)) { ?>
                            <p id="error_message">
                                <?php echo $errorSignUp; ?>
                            </p>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>