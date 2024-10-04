<?php
ob_start();
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin_Master") {
        header("Location:master/");
        exit;
    } else {
        header("Location:admin/");
        exit;
    }
}
else{
    header("Location:index.php");
    exit;
}
?>
