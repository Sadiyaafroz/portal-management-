<?php
include "config.php";
include_once "approveSql.php";
if (isset($_GET['recordId'])){
    $recordId = $_GET['recordId'];
    $common = new Common();
    $delete = $common->approveRecordById($connection,$recordId);
    if ($delete){
        echo '<script>alert("Record approved successfully !")</script>';
        echo '<script>window.location.href="admin.php";</script>';
    }
}