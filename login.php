<!DOCTYPE html>
<html>

<head>
    <title>Login or Sign Up</title>
    <link rel="stylesheet" type="text/css" href="./css/login.css" />
    <link rel="stylesheet" type="text/css" href="./css/navbar.css" />
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

                        <button class="button" type="submit">Login</button>
                    </div>
                </form>
            </div>

            <div class="vertical"></div>
            <div id="create_acc_wrapper">
                <p class="smaller_title">Create Account</p>
                <form action="login.php" method="post">
                    <div id="create_acc_form">
                        <label for="username">Enter your email: </label>
                        <input type="email" placeholder="Enter Email" name="email" required>

                        <label for="username">Create a Username: </label>
                        <input type="text" placeholder="Enter Username" name="user" required>

                        <label for="password">Create a Password: </label>
                        <input type="password" placeholder="Enter Password" name="pass" required>

                        <button class="button" type="submit">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if (!empty($_POST)) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($username == "admin" && $password == "admin") {
            $status = setcookie("logged_in", "true");
            header("location: profile.php");
        }
    }
    ?>
</body>

</html>