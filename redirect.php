<?php
if (isset($_SESSION['userid'])) {
    if (isset($_SESSION['role']))
        header("location:admin_select.php");
    else
        header("location:index.php");
}
else{
    if(!str_contains($_SERVER["SCRIPT_NAME"], 'login.php'))
        header("location:login.php");
}
?>