<?php
include "../../../config/connect.php";

if (isset($_POST['exam_id'])) {
// Fetch subject data from the database
    $sql = "SELECT subject  FROM subject";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $units = array();
        while ($row = $result->fetch_assoc()) {
            $units[] = $row;
        }
        echo json_encode($units);
    } else {
        echo "No subjects found";
    }
}

$con->close();
?>
