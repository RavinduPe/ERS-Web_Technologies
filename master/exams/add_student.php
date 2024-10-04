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

if (isset($_POST['exam_id']) && isset($_POST['level']) && isset($_POST['type']) && isset($_POST['regNo'])) {
    $exam_id = $_POST['exam_id'];
    $level = $_POST['level'];
    $type = $_POST['type'];
    $regNo = $_POST['regNo'];
    $checkQuery = "SELECT * FROM student_check WHERE regNo = '$regNo'";
    $result = $con->query($checkQuery);
    if ($result->num_rows == 0) {
        echo "Registration no not found";
    }else{
        // Check if the student is already registered for this exam, level, and type
        $checkQuery = "SELECT * FROM exam_reg_excep WHERE exam_id = $exam_id AND level = $level AND type = '$type' AND regNo = '$regNo'";
        $result = $con->query($checkQuery);
        if ($result->num_rows == 0) {
            // If the student is not registered, insert the registration
            $insertQuery = "INSERT INTO exam_reg_excep (exam_id, level, type, regNo) VALUES ($exam_id, $level, '$type', '$regNo')";
            $stmt = $con->query($insertQuery);
            echo "Success"; // Indicate successful insertion
        } else {
            echo "AlreadyAdded"; // Student is already registered
        }
    }



} else {
    echo "Error"; // Data not received properly
}
?>
<?php
