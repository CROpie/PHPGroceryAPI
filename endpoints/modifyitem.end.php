<?php

function handleModifyItem() {

// grab data
$jsonData = file_get_contents('php://input');

$editedItemData = json_decode($jsonData, true); 

$itemId = trim($editedItemData["itemId"]);
$itemName = trim($editedItemData["name"]);
$itemAmount = trim($editedItemData["amount"]);

// grab token
$authHeader = $_SERVER["HTTP_AUTHORIZATION"];

if (!$authHeader) {
    throw new Exception("No authorization header provided.");
}

// instantiate registerContr class
include "../classes/dbh.classes.php";
include "../classes/items.classes.php";
include "../classes/items-contr.classes.php";
include "../classes/jwt-handler.classes.php";
include "../utils/secretkey.utils.php";

$jwtHandler = new JWTHandler($secretKey);

$items = new ItemsContr($authHeader, $jwtHandler);

// decoded JWT, getItems if user is authorized
// any errors will be caught by try/catch in API index
$itemsData = $items->modifyItem($itemId, $itemName, $itemAmount);

// return the itemsData if retrieved successfully. If not it will be handled by the try/catch
return $itemsData;

}