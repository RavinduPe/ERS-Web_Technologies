<?php

include "../config/connect.php";

if(isset($_POST["regId"])) {
    $regId = $_POST["regId"];

    $deleteUnitsQuery = "DELETE FROM reg_units WHERE regId = $regId";
    $deleteUnitsResult = mysqli_query($con, $deleteUnitsQuery);

    if ($deleteUnitsResult) {
        $deleteRegistrationQuery = "DELETE FROM stud_exam_reg WHERE regId = $regId";
        $deleteRegistrationResult = mysqli_query($con, $deleteRegistrationQuery);

        if ($deleteRegistrationResult) {
            header("Location: index.php?success=Registration has been deleted.");
            exit();
        } else {
            header("Location: index.php?error=Failed to delete registration. Please try again.");
            exit();
        }
    } else {
        header("Location: index.php?error=Failed to delete registration. Please try again.");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
mysqli_close($con);

?>
