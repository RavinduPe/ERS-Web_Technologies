<?php
ob_start();
session_start();
$_SESSION = array();
session_destroy();
header("location:login.php");
exit();
?>