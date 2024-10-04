<?php
ob_start();
session_start();
if (isset($_SESSION['userid'])) {
    if (isset($_SESSION['role']))
        header("location:../admin_select.php");
    else
        header("location:../student");
}?>