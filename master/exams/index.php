<?php
ob_start();
if(!isset($_SESSION)){session_start();}
require('../../config/connect.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin_Master") {
    header("location:../../login.php");
    exit();
}

include("../../config/connect.php");
require_once("../../config/adminName.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
            rel="shortcut icon"
            href="../../assets/img/logo/ERS_logo_icon.ico"
            type="image/x-icon"/>
    <title>ERS | Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/master.css" />
    <script
    src="https://kit.fontawesome.com/5ce4b972fd.js"
    crossorigin="anonymous"></script>
</head>


<body  class="bg-gray-200 sm:text-xs xl:text-sm 2xl:text-base">

<?php
    $rpath = "../";
    include("../navbar.php")
?>

<div id="nextSibling" class="transition-all ml-[300px] h-auto flex items-center justify-center py-20">
    <div class="card drop-shadow-xl">
        <?php
        $error =array();
        if (isset($_GET['page'])) {
            if ($_GET['page'] == "add") {
                include("exam_edit.php");
            }
            if ($_GET['page'] == "edit") {
                include("exam_edit.php");
            }

        } else if (isset($_POST['ed_exm'])) {
            $exam_id = $_POST['exam_id'];
            $status = $_POST['status'];
            $close_date = $_POST['close_date'];
            $update_exm = "UPDATE `exam_reg` SET `status` = '$status', `closing_date` = '$close_date' WHERE exam_id = '$exam_id'";
            $run_sql = mysqli_query($con, $update_exm);
            if (!$run_sql) {
                echo "<h1>error </h1>" . $con->error;
                include("exam_edit.php");
            } else
                header("Location:../exams");


        } else if (isset($_POST['add_exm'])) {
            $acYear = intval($_POST['academic_year']);
            $semester = intval($_POST['semester']);
            $status = $_POST['status'];
            $close_date = $_POST['close_date'];
            $cur_date =date("Y-m-d");
            $add_exam = "INSERT INTO `exam_reg` (`academic_year`, `semester`, `status`, `closing_date`, `date_created`) 
                    VALUES ('$acYear', '$semester', 'draft', '$close_date','$cur_date')";

            try {
                $run_sql = mysqli_query($con, $add_exam);
                include "exam_mgmt.php";
            } catch (Exception $e) {
                $error['add error']  ="Exam cannot be added!";
                include("exam_mgmt.php");
            }


        } else {
            include "exam_mgmt.php";
        }
        ?>
    </div>
</div>



<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<script>
    let subMenu = document.getElementById("subMenu");

    function toggleMenu() {
        subMenu.classList.toggle("open-menu");
    }
</script>


</body>
</html>


