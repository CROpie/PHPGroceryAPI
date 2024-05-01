<?php

class Login extends Dbh {

    protected function getUser($username, $password) {
        $sql = "SELECT * 
                FROM Users 
                WHERE username = ?;";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the SELECT query in Login");
        }

        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();

        $result->free();
        $stmt->close();

        if (!count($userData)) {
            throw new Exception("User not found.");
        }

        $hashedPassword = $userData["password"];
        if (!password_verify($password, $hashedPassword)) {
            throw new Exception("Incorrect password.");
        }

        // using both PHP session and JS session is redundant?
        session_start();
        $_SESSION["userId"] = $userData["userId"];
        $_SESSION["username"] = $userData["username"];

        return $userData;
    }
}