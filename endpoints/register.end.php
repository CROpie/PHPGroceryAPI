<?php

function handleRegister() {

// grab data
$username = $_POST["username"];
$password = $_POST["password"];
$passwordRepeat = $_POST["passwordRepeat"];

// instantiate registerContr class
include "../classes/dbh.classes.php";
include "../classes/register.classes.php";
include "../classes/register-contr.classes.php";

$register = new RegisterContr($username, $password, $passwordRepeat);

// run validation and user registration
// any errors will be caught by try/catch in API index
$register->registerUser();

// no errors = success
return true;

}