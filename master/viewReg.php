<?php

$getCurrentExam = "SELECT * FROM exam_reg WHERE status = 'closed';";
$result = mysqli_query($con, $getCurrentExam);
if ($result->num_rows > 0) {
    $curExam = mysqli_fetch_assoc($result);
}
else{
    header("Location:index.php");
}
$aExamID= $curExam['exam_id'];
$form ="select";
?>

<?php include("../registrationList/reg_list.php") ?>


