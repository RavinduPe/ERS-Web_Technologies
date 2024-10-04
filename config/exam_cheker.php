<?php
require_once("connect.php");

$tableName = 'exam_reg';

$currentDate = date('Y-m-d');

$sql = "UPDATE $tableName SET `status` = 'closed' WHERE (`status` = 'registration' OR `status` = 'draft') AND `closing_date` <= '$currentDate'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

$updatedRows = mysqli_affected_rows($con);

if ($updatedRows > 0) {
    echo "Updated $updatedRows exams to 'closed' state.";
} else {
    echo "No exams were updated.";
}

mysqli_close($con);

?>