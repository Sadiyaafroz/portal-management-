<?php
    session_start();
    unset($_SESSION['loggedIn']);
    session_destroy();
	$url="../../YT/index.php";
    header('Location: '.$url);
    exit();
?>