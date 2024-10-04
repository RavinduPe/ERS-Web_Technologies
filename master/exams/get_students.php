<?php
ob_start();
require_once('../../config/connect.php');
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin_Master") {
    header("location:../../login.php");
    exit();
}
if (isset($_GET['exam_id']) && isset($_GET['level']) && isset($_GET['type'])) {
    $examId = $_GET['exam_id'];
    $level = $_GET['level'];
    $type = $_GET['type'];

    $query = "SELECT regNo FROM exam_reg_excep WHERE exam_id = $examId AND level = $level AND type = '$type'";

    $result = $con->query($query);

    $students = array();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
} else {
    echo json_encode(array()); // Return an empty array if parameters are missing
}
?>
