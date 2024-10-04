<?php
ob_start();
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin_Master") {
    header("location:../../login.php");
    exit();
}
require_once('../../config/connect.php');
if(!isset($_SESSION)){session_start();}
if (isset($_POST['exam_id']) && isset($_POST['level']) && isset($_POST['type']) && isset($_POST['regNo'])) {
    // Retrieve data from POST request
    $examId = $_POST['exam_id'];
    $level = $_POST['level'];
    $type = $_POST['type'];
    $registrationNumber = $_POST['regNo'];

    // Add code to remove the student from the database using SQL DELETE statement
    // Make sure to check for any error conditions and return "Success" or an error message

    // Example code to remove a student (update with your database structure)
    $sql = "DELETE FROM exam_reg_excep WHERE exam_id = $examId AND level = $level AND type = '$type' AND regNo = '$registrationNumber'";
    if ($con->query($sql) === true) {
        echo "Success";
    } else {
        echo "Error: " . $con->error;
    }
}

