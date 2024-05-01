<?php

function handleLogin() {

// grab data
$username = $_POST["username"];
$password = $_POST["password"];

// instantiate registerContr class
include "../classes/dbh.classes.php";
include "../classes/login.classes.php";
include "../classes/login-contr.classes.php";

$login = new LoginContr($username, $password);

// run validation and user log in
// any errors will be caught by try/catch in API index
$userData = $login->loginUser();

// return the userData if it exists, and if not it will be handled by the try/catch
// this could be the pathway for getting the JWT to the client..?
return $userData;

}