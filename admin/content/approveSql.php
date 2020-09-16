<?php
class Common
{
    public function getAllRecords($connection) {
        $query = "SELECT * FROM fileup";
        $result = $connection->query($query) or die("Error in query1".$connection->error);
        return $result;
    }

    public function approveRecordById($connection,$recordId) {
	
        $query = "INSERT INTO users SELECT * FROM pendinguser WHERE id='$recordId';";
		
        $result = $connection->query($query) or die("Error in query2".$connection->error);
		 $query = "DELETE FROM fileup WHERE id='$recordId'";
		 $result = $connection->query($query) or die("Error in query2".$connection->error);
        return $result;
    }
}