

<?php

/**
 * Controller for Register functionality
 * Takes input from the user, sanitizes and validates it, then calls upon Register to insert it into the DB
 * If validation fails, or if there is a problem with the database, throw an Exception
 * The error message will be passed back to the try/catch -> json to index.php (script.js)
 * (which means, only 1 error at a time will be shown to the user..)
 */

class RegisterContr extends Register {

private $username;
private $password;
private $passwordRepeat;

    public function __construct($username, $password, $passwordRepeat) {
        $this->username = $username;
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
    }

    public function registerUser() {

        // sanitize user input

        $this->performValidation();

        $this->setUser($this->username, $this->password);
    }

    private function performValidation() {
        $this->emptyInput();
        $this->invalidUsername();
        $this->passwordMatch();
        $this->userAlreadyExists();
    }

    private function emptyInput() {
        if (empty($this->username) || empty($this->password) || empty($this->passwordRepeat)) {
            throw new Exception("Not all fields were filled out.");
        }
    }

    // usernames must consist only of alphanumeric characters
    private function invalidUsername() {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->username)) {
            throw new Exception("Username contained special characters.");
        }
    }

    private function passwordMatch() {
        if ($this->password !== $this->passwordRepeat) {
            throw new Exception("The passwords don't match.");
        }
    }

    private function userAlreadyExists() {
        if ($this->findUser($this->username)) {
            throw new Exception("That username is already taken.");
        }
    }

}