<?php

function handleLogin() {

// grab data
$username = $_POST["username"];
$password = $_POST["password"];

// instantiate registerContr class
include "../classes/dbh.classes.php";
include "../classes/login.classes.php";
include "../classes/login-contr.classes.php";
include "../classes/jwt-handler.classes.php";
include "../utils/secretkey.utils.php";

$jwt = new JWTHandler($secretKey);

$login = new LoginContr($username, $password, $jwt);

// run validation and user log in, generate a JWT
// any errors will be caught by try/catch in API index
$jwt = $login->loginUser();

// return the jwt if is created successfully. If not it will be handled by the try/catch
return $jwt;

}