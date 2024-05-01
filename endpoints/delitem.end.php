<?php

function handleDelItem() {

    // grab token
    $authHeader = $_SERVER["HTTP_AUTHORIZATION"];

    // get id
    $id = $_GET["id"];

    if (!$authHeader) {
        throw new Exception("No authorization header provided.");
    }

    // instantiate ItemsContr class
    include "../classes/dbh.classes.php";
    include "../classes/items.classes.php";
    include "../classes/items-contr.classes.php";
    include "../classes/jwt-handler.classes.php";
    include "../utils/secretkey.utils.php";

    $jwtHandler = new JWTHandler($secretKey);

    $items = new ItemsContr($authHeader, $jwtHandler);

    // decode JWT, delete an item if user is authorized
    // any errors will be caught by try/catch in API index
    $itemsData = $items->delItem($id);

    // return the itemsData if retrieved successfully. If not it will be handled by the try/catch
    return $itemsData;

}