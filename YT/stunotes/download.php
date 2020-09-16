<?php
include "config.php";
include_once "approveSql.php";
if (isset($_GET['recordId'])){
    $recordId = $_GET['recordId'];
   

	$filename = $recordId;
	$filepath = '../images/' . $filename;
	if(!empty($filename) && file_exists($filepath)){

//Define Headers
  header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg');
    header('Content-Disposition: attachment; filename='.basename($image));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: public');
    header('Pragma: public');
    ob_clean();


		readfile($filepath);
		exit;

	}
	else{
		echo "This File Does not exist.";
	}




}

?>