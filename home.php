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