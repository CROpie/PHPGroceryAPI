<?php

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

header("Content-Type: application/json");
$result = array(
    "success" => true,
    "message" => '',
);

switch ($method | $uri) {

    /**
    * Path: POST /api/auth/register
    * Task: Register new users to the system
    */
    case ($method == "POST" && $uri == "/api/auth/register"):
        // import handleRegister function
        include "../endpoints/register.end.php";

        try {
            $outcome = handleRegister();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        echo json_encode($result);
        break;

    /**
    * Path: POST /api/auth/register
    * Task: Register new users to the system
    */
    case ($method == "POST" && $uri == "/api/auth/login"):
        // import handleRegister function
        include "../endpoints/login.end.php";

        try {
            $userData = handleLogin();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["userData"] = $userData;
        echo json_encode($result);
        break;

    /**
    * Path: GET /api/auth/logout
    * Task: Log out of the current session
    */
    case ($method == "GET" && preg_match("/\/api\/auth\/logout/", $uri)):
        // not all that useful at the moment, but in future,
        // maybe will want to clear a token from storage
        include "../endpoints/logout.end.php";

        try {
            $outcome = handleLogout();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        echo json_encode($result);
        break;

    /**
    * Path: ?
    * Task: this path doesn't match any of the defined paths
    *       throw an error
    */
    default:
        $result["success"] = false;
        $result["message"] = "unknown api path";
        echo json_encode($result);
        break;

    }

?>