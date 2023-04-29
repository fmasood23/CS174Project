<?php

$signed_in = '<nav>
<div id="logo">CalorieSmart</div>

<label for="drop" class="toggle">Menu</label>
<input type="checkbox" id="drop" />
<ul class="menu">
    <li><a href="./home.php">Home</a></li>
    <li><a href="./about.php">About Us</a></li>
    <li><a href="./tracker.php">Calorie Tracker</a></li>
    <li><a href="./profile.php">Profile</a></li>
</ul>
</nav>';

$default = '<nav>
<div id="logo">CalorieSmart</div>

<label for="drop" class="toggle">Menu</label>
<input type="checkbox" id="drop" />
<ul class="menu">
    <li><a href="./home.php">Home</a></li>
    <li><a href="./about.php">About Us</a></li>
    <li><a href="./tracker.php">Calorie Tracker</a></li>
    <li><a href="./login.php">Login / Sign Up</a></li>
</ul>
</nav>';


if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == "true") {
    echo $signed_in;
} else {
    echo $default;
}
?>