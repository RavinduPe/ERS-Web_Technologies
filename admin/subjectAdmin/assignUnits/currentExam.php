<?php
$getCurrentExam = "SELECT * FROM exam_reg WHERE status = 'draft'";
$result = mysqli_query($con, $getCurrentExam);

if ($result->num_rows > 0) {
    $curExam = mysqli_fetch_assoc($result);
}
?>
