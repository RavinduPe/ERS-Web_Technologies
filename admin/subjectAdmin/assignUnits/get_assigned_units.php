<?php

include "../../../config/connect.php";

if (isset($_POST['subject']) && isset($_POST['level']) && isset($_POST['exam_id']) && isset($_POST['type'])) {
    $exam_id = $_POST['exam_id'];
    $level = $_POST['level'];
    $subject = $_POST['subject'];
    $type = $_POST['type'];
    // Fetch unit data from the database
    $get_query = "SELECT `unit`.*
                                FROM `unit_sub_exam`
                                LEFT JOIN `unit` ON `unit_sub_exam`.`unitId` = `unit`.`unitId`
                                WHERE unit_sub_exam.exam_id = $exam_id
                                        AND subject = '$subject'
                                        AND type = '$type'
										AND level = $level;";

    $result = mysqli_query($con, $get_query);

    if ($result->num_rows > 0) {
        $units = array();
        while ($row = $result->fetch_assoc()) {
            $units[] = $row;
        }
        echo json_encode($units);
    } else {
        // No units found, return a JSON response indicating so
        echo json_encode(array());
    }
}

$con->close();

