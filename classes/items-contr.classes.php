

<?php

/**
 * Controller for Items functionality
 * Talks to JWTHandler to see if user is allowed to perform the particular operation
 * Talk to Items-Contr to perform that operation
 */

class ItemsContr extends Items {

    private $authHeader;
    private $jwtHandler;


    public function __construct($authHeader, $jwtHandler) {
        $this->authHeader = $authHeader;
        $this->jwtHandler = $jwtHandler;
    }

    public function getItems() {
        // not sure if want to use the payload yet, but it should be available
        $payload = $this->jwtHandler->decodeJWT($this->authHeader);

        return $this->getItemsFromDB();
    }

    public function delItem($id) {
        $payload = $this->jwtHandler->decodeJWT($this->authHeader);

        $this->delItemFromDB($id);

        return $this->getItemsFromDB();
    }

}