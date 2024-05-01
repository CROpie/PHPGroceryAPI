<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./script.js" defer></script>
    <title>Document</title>
</head>
<body>
    <?php
        if (isset($_SESSION["userId"])) {
            echo "<h2>Welcome, {$_SESSION['username']}!</h2>";
        } else {
            echo "<h2>Please log in to continue.</h2>";
        }
    ?>

    <h3>Register</h3>
    <form id="registerForm">
        <input type="text" name="username" placeholder="username" value="chris"/>
        <input type="password" name="password" placeholder="password" value="fishandchips"/>
        <input type="password" name="passwordRepeat" placeholder="passwordRepeat" value="fishandchips"/>
        <button type="submit" name="registerBtn" id="registerBtn">Submit</button>
    </form>

    <h3>Log In</h3>
    <form id="loginForm">
        <input type="text" name="username" placeholder="username" value="chris"/>
        <input type="password" name="password" placeholder="password" value="fishandchips"/>
        <button type="submit" name="loginBtn" id="loginBtn">Submit</button>
    </form>

    <h3>Log Out</h3>
    <button id="logoutBtn">Log Out</button>
</body>
</html>