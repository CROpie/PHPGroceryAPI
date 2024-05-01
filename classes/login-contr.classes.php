

<?php

/**
 * Controller for Login functionality
 * Takes input from the user, sanitizes and validates it, then calls upon Login to insert it into the DB
 * If validation fails, or if there is a problem with the database, throw an Exception
 * The error message will be passed back to the try/catch -> json to index.php (script.js)
 */

class LoginContr extends Login {

private $username;
private $password;
private $jwt;

    public function __construct($username, $password, $jwt) {
        $this->username = $username;
        $this->password = $password;
        $this->jwt = $jwt;
    }

    public function loginUser() {
        $this->performValidation();
        $userData = $this->getUser($this->username, $this->password);
        return $this->jwt->generateJWT($userData);
    }

    private function performValidation() {
        $this->emptyInput();
    }

    private function emptyInput() {
        if (empty($this->username) || empty($this->password)) {
            throw new Exception("Not all fields were filled out.");
        }
    }

}