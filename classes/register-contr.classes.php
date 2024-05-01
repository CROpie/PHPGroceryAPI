

<?php

/**
 * Controller for Register functionality
 * Takes input from the user, sanitizes and validates it, then calls upon Register to insert it into the DB
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
        // perform validation

        $this->setUser($this->username, $this->password);
    }

}