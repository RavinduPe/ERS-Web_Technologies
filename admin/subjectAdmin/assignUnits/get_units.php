<?php
include "../../../config/connect.php";

if (isset($_POST['subject']) && isset($_POST['level']) && $_POST['subject'] !== "" && $_POST['level'] !== "") {
    // Fetch unit data from the database
    $sql = "SELECT unitId, unitCode, name, acYearAdded FROM unit WHERE subject = '" . $_POST['subject'] . "' AND level ='" . $_POST['level'] . "' ORDER BY unitCode ASC , acYearAdded DESC";
    $result = $con->query($sql);

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
?>
