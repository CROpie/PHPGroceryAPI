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
                WHERE itemId = $id;";

        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Couldn't execute the DELETE query in Items");
        }
        $stmt->close();

    }
}