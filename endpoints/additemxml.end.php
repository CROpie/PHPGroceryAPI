<?php
function handleAddItemXml() {

// grab raw data
$xmlRawData = file_get_contents("php://input");

// load xml data
$xml = new SimpleXMLElement($xmlRawData);

// process xml
$itemName = (string) $xml->name;
$itemAmount = (int) $xml->amount;


/* ALL THIS IS IDENTICAL NO MATTER HOW THE DATA IS SENT UP */

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
$itemsData = $items->addItem($itemName, $itemAmount);

// return the itemsData if retrieved successfully. If not it will be handled by the try/catch
return $itemsData;
}