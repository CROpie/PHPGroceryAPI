<?php

class Items extends Dbh {

    protected function getItemsFromDB() {
        $sql = "SELECT * 
                FROM Items;";
        
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the SELECT query in Items");
        }

        $result = $stmt->get_result();

        $itemsData = array();

        while ($row = $result->fetch_assoc()) {
            $itemsData[] = $row;
        }

        $result->free();
        $stmt->close();

        return $itemsData;
    }

    protected function delItemFromDB($id) {
        $sql = "DELETE 
                FROM Items
                WHERE itemId = ?;";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the DELETE query in Items");
        }
        $stmt->close();

    }

    protected function addItemToDB($itemName, $itemAmount) {
        $sql = "INSERT INTO Items (name, amount)
                VALUES (?, ?);";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bind_param("si", $itemName, $itemAmount);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the INSERT query in Items");
        }
        $stmt->close();

    }

    protected function modifyItemInDB($itemId, $itemName, $itemAmount) {
        $sql = "UPDATE Items
                SET 
                name = ?,
                amount = ?
                WHERE itemId = ?;";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bind_param("sis", $itemName, $itemAmount, $itemId);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the UPDATE query in Items");
        }
        $stmt->close();

    }
}