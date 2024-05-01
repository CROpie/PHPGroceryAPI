<?php

class Register extends Dbh {

    protected function setUser($username, $password) {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO 
                Users (username, password)
                VALUES (?, ?);
                ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the INSERT query in Register");
        }

        $stmt->close();

        // need to return something?
    }

    // looks to see if the user is in the database.
    // returns true if they are, and false if they are not.
    protected function findUser($username) {
        $sql = "SELECT username 
                FROM Users 
                WHERE username = ?;";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the SELECT query in Register");
        }

        $result = $stmt->get_result();

        // should be array(1) (found) or array(0) (no entries for that username)
        $userData = $result->fetch_assoc();

        $result->free();
        $stmt->close();

        // if they aren't in the database, return false
        if (!count($userData)) {
            return false;
        }
        return true;

    }

}