<?php
$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

// header("Content-Type: application/json");
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
    * Path: POST /api/auth/login
    * Task: Login a user, and if successful, return a JWT
    */
    case ($method == "POST" && $uri == "/api/auth/login"):
        include "../endpoints/login.end.php";

        try {
            $JWT = handleLogin();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["JWT"] = $JWT;
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
            handleLogout();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        echo json_encode($result);
        break;

    /**
    * Path: GET /api/data/items
    * Task: Retrieve items for authorized users
    */
    case ($method == "GET" && $uri == "/api/data/items"):
        include "../endpoints/getitems.end.php";

        try {
            $itemsData = handleGetItems();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["data"] = $itemsData;
        echo json_encode($result);
        break;

    /**
    * Path: POST /api/data/items
    * Task: Add a new item to the database
    */
    case ($method == "POST" && $uri == "/api/data/items"):
        include "../endpoints/additem.end.php";

        try {
            $itemsData = handleAddItem();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["data"] = $itemsData;
        echo json_encode($result);
        break;

    /**
    * Path: PUT /api/data/items
    * Task: Modify an item in the database
    */
    case ($method == "PUT" && $uri == "/api/data/items"):
        include "../endpoints/modifyitem.end.php";

        try {
            $itemsData = handleModifyItem();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["data"] = $itemsData;
        echo json_encode($result);
        break;

    /**
    * Path: DELETE /api/data/items?id=<int>
    * Task: Delete a particular entry, return updated data
    */
    case ($method == "DELETE" && preg_match("/\/api\/data\/items\?id=/", $uri)):
        include "../endpoints/delitem.end.php";

        try {
            $itemsData = handleDelItem();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["data"] = $itemsData;
        echo json_encode($result);
        break;

    /**
    * Path: POST /api/data/items
    * Task: Add a new item to the database
    */
    case ($method == "POST" && $uri == "/api/data/xmlitems"):
        include "../endpoints/additemxml.end.php";
        include "../utils/arraytoxml.utils.php";

        try {
            $itemsData = handleAddItemXml();
        } catch (Exception $e) {
            $result["success"] = false;
            $result["message"] = $e->getMessage();
        }
        $result["data"] = $itemsData;

        header("Content-Type: application/xml");
        $myString = arrayToXmlString($result);
        echo $myString;
        // echo arrayToXmlString($result);
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