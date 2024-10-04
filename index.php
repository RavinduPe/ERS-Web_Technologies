<?php
ob_start();
session_start();
require_once("config/exam_cheker.php");
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}
elseif (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin_Master") {
        header("Location:master");
        exit;
    } else {
        header("Location:admin");
        exit;
    }
}
else{
    header("Location:student");
    exit;
}

