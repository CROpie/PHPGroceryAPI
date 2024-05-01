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

}