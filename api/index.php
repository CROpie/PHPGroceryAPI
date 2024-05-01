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
    * Path: GET /api/users
    * Task: show all the users
    */
    case ($method == "POST" && $uri == "/api/auth/register"):
        // import handleRegister function
        include "../endpoints/login.end.php";

        try {
            $outcome = handleRegister();
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