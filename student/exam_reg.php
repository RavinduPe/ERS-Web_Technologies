<?php
ob_start();
session_start();
include("../config/connect.php");
if (!isset($_SESSION['userid'])) {
    header("location:../login.php");
    exit();
}
elseif (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin_Master") {
        header("Location:../master");
        exit;
    } else {
        header("Location:../admin");
        exit;
    }
}

$edit = false;
$regDetail =array();
if(isset($_GET['edit']) && isset($_POST['regId'])){
    $edit = true;
    $regId =$_POST['regId'];
    $selectSQL = "SELECT * FROM stud_exam_reg WHERE regId = '$regId';";
    $selectQuery = mysqli_query($con, $selectSQL);
    $regObj = mysqli_fetch_assoc($selectQuery);
    $regDetail['type'] = $regObj['type'];
    $regDetail['combination'] = $regObj['combId'];
    $regDetail['level'] = $regObj['level'];
    $examUnitId =array();
    $sql = "SELECT exam_unit_id
        FROM reg_units
        WHERE regid = $regId";

    $result = mysqli_query($con,$sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $examUnitId[] = $row["exam_unit_id"];
        }
    }
}

$errors = array();
$units = array();
$regNo = $_SESSION['userid'];

$selectSQL = "SELECT * FROM student WHERE regNo = '$regNo';";
$selectQuery = mysqli_query($con, $selectSQL);
$user = mysqli_fetch_assoc($selectQuery);
$profile_img = isset($user['profile_img']) ? $user['profile_img'] : "blankProfile.png";

$examDetailsSQL = "SELECT * FROM `exam_reg` WHERE status = 'registration' ORDER BY exam_id DESC LIMIT 1;";
$examDetails = mysqli_query($con, $examDetailsSQL);
$exam = mysqli_fetch_assoc($examDetails);
$exam_id = $exam['exam_id'];
if (mysqli_num_rows($examDetails) == 0) {
    header("Location: index.php?error=Sorry! There is no exams to register");
    exit();
}

//registration restriction
$stud_year = substr($regNo,0,4);
$exam_year = $exam['academic_year'];
$exams_type =array("proper"=> array(), "repeat"=>array());


$calcyear = ($exam_year - $stud_year) +1;
$can_repeat =($calcyear) < 8;
$prop_year = ($calcyear > 4)?5:$calcyear;
if($calcyear < 5)
    array_push($exams_type["proper"],$prop_year);
if($can_repeat)
    for ($i = 1; $i < $prop_year; $i++) {
        array_push($exams_type["repeat"],$i);
    }

$typeDetailsSQL = "SELECT level,type FROM `exam_reg_excep` WHERE regNo = '$regNo' and  exam_id = $exam_id";
$typeDetails = mysqli_query($con, $typeDetailsSQL);
while($type = mysqli_fetch_assoc($typeDetails)){
    array_push($exams_type[$type['type']],$type['level']);
}
if ((count($exams_type["repeat"]) + count($exams_type["proper"])) == 0){
    header("Location: index.php?error=Sorry! You have no exams to register!<br>Contact admin if its a mistake!");
    exit();
}

$exams_type["repeat"] = array_unique($exams_type["repeat"]);
$exams_type["proper"] = array_unique($exams_type["proper"]);
sort($exams_type["repeat"]);
sort($exams_type["proper"]);


// getting the index number
$query = "SELECT * FROM `exam_stud_index` WHERE `regNo`= '$regNo' AND `exam_id` = $exam_id";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)) {
    $row = mysqli_fetch_assoc($result);
    $indexNo = $row['indexNo'];
} else {
    $indexNo = "Contact the Dean office";
    header("Location: index.php?error=Sorry! your index number has not been assigned!<br>Contact the Dean office!");
    exit();
}

$selectSQL1 = "SELECT * FROM combination";
$combinationList = mysqli_query($con, $selectSQL1);

function setValue($fieldname) {
    if (isset($_POST[$fieldname])) {
        echo $_POST[$fieldname];
    }
    if ($GLOBALS['edit']) {
        echo $GLOBALS['regDetail'][$fieldname];
    }
}

function setChecked($fieldName, $fieldValue) {
    if (isset($_POST[$fieldName]) && $_POST[$fieldName] == $fieldValue) {
        echo "checked='checked'";
    }
}

function setSelected($fieldName, $fieldValue) {
    if (isset($_POST[$fieldName]) && $_POST[$fieldName] == $fieldValue) {
        echo  "selected='selected'";
    }
    if ($GLOBALS['edit'] && ($GLOBALS['regDetail'][$fieldName] == $fieldValue)) {
        echo  "selected='selected'";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="shortcut icon"
        href="../assets/img/logo/ERS_logo_icon.ico"
        type="image/x-icon" />
    <title>ERS | Exam Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script
    src="https://kit.fontawesome.com/5ce4b972fd.js"
    crossorigin="anonymous"></script>
    <script>
        var selectedUnits = [];
    </script>

</head>
<body class="bg-slate-200 dark:bg-gray-700 dark:text-white" id="exam">
    <?php if (isset($_GET['error'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7 bg-white text-black dark:bg-gray-800 dark:text-white" action="index.php" method="POST">
                <p class="text-center"><?php echo $_GET['error'] ?></p>
                <input class="btn fill-btn" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } elseif (isset($_GET['success'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7 bg-white text-black dark:bg-gray-800 dark:text-white" action="index.php" method="POST">
                <p class="text-center text-green-700"><?php echo $_GET['success'] ?></p>
                <input class="btn fill-btn !bg-green-700" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } ?>


    <nav class="w-full h-[15vh] min-h-fit drop-shadow-md bg-white fixed top-0 left-0 text-black dark:bg-gray-900 dark:text-white">
        <div class="w-10/12 h-full m-auto flex items-center justify-between">
            <a href="index.php">
                <img src="../assets/img/logo/ERS_logo.gif" alt="logo" class="w-28 align-middle">
            </a>
            <ul>
                <?php if (!isset($profile_img)) { ?>
                    <li onclick="openMenu()" class="py-2 px-[14px] bg-[var(--primary)] rounded-full drop-shadow-md cursor-pointer lh:relative">
                        <i class="fa-solid fa-user text-2xl text-[#dfeaff]"></i>
                    </li>
                <?php } else { ?>
                    <li onclick="openMenu()" class="w-10 h-10 lg:w-12 lg:h-12 rounded-full drop-shadow-md cursor-pointer ring-4">
                        <img src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img" class="w-full h-full rounded-full">
                    </div>
                <?php } ?>
            </ul>

        </div>
        <div class="hidden top-[14.8vh] right-0 h-56 w-full bg-white -translate-y-full z-20 transition-transform lg:top-[16vh] lg:drop-shadow-2xl lg:right-24 lg:w-56 lg:translate-x-full lg:h-72 lg:rounded-tl-3xl lg:rounded-br-3xl lg:text-gray-800 text-black dark:bg-gray-800 dark:text-white" id="user-menu">
            <ul class="w-11/12 h-full m-auto flex flex-col items-center justify-around text-center">
                <li class="mt-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="exam_reg.php">Exam Registration</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="index.php">Dashboard</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="contact.php">Contact</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class="mb-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="w-11/12 lg:w-1/2 mx-auto mt-[22vh] mb-20 dark:bg-gray-800 dark:text-white ">
        <div class="card bg-white text-black dark:bg-gray-800 dark:text-white">
            <div class="w-11/12 mx-auto h-fit dark:bg-gray-800 dark:text-white ">

                <?php
                if (isset($_POST["step"])) {
                    if ($_POST["step"] == '1') {
                            processStep1();
                    } else if ($_POST["step"] == '2') {
                            processStep2();
                    }
                    else if ($_POST["step"] == '3') {
                        processStep3();
                    }
                } else {
                    displayStep1();
                }
                function processStep1() {
                    global $con, $exam;
                    $exam_id = $exam['exam_id'];
                    $type = $_POST['type'];
                    $level = $_POST['level'];
                    $combination = $_POST['combination'];

                    $unitSQL = "
                        SELECT DISTINCT u.unitId, u.unitCode, u.name
                        FROM unit u
                        INNER JOIN combination_subjects cs ON u.subject = cs.subject
                        INNER JOIN unit_sub_exam usexam ON u.unitId = usexam.unitId
                        WHERE cs.combinationID = $combination
                        AND u.level = $level
                        AND usexam.exam_id = $exam_id
                        AND usexam.type = '$type';
                    ";

                    $unitsQueryResult = mysqli_query($con, $unitSQL);
                    //$units = mysqli_fetch_assoc($unitsQueryResult);
                    //print_r($unitsQueryResult->num_rows);
                   // exit;
                    if ($unitsQueryResult) {
                        if (mysqli_num_rows($unitsQueryResult) == 0) {
                            header("Location: index.php?error=No units were assign to this combination.");
                            exit();
                            echo "error";
                        }
                    } else {
                        header("Location: index.php?error=Something-went-wrong");
                        exit();
                    }

                    displayStep2($unitsQueryResult);
                }

                function processStep2() {
                    if (isset($_POST["submit"]) and $_POST["submit"] == "< Back") {
                        displayStep1();
                    }
                    elseif ($_POST['submit'] == "Next >" && $_POST['type'] == "repeat") {
                        displayStep3();
                    }
                    else {
                        global $con, $exam, $regNo;
                        $exam_id = $exam['exam_id'];
                        $type = $_POST['type'];
                        $level = $_POST['level'];
                        $editRegId = (isset($_POST['regId']))?$_POST['regId']:false;
                        $combination = $_POST['combination'];
                        $regUnits = $_POST['units'];
                        $date =date('Y-m-d');

                        if($editRegId){

                            $updateQuery = "UPDATE stud_exam_reg SET
                                                type = '$type',
                                                level = $level,
                                                combId = $combination,
                                                reg_date = '$date'
                                                WHERE regId = $editRegId";

                            if (mysqli_query($con, $updateQuery)) {

                                $deleteQuery = "DELETE FROM reg_units WHERE regId = $editRegId";
                                if (mysqli_query($con, $deleteQuery)) {
                                    $inserted = true;
                                    foreach ($regUnits as $unitId) {
                                        $reg_units_sql = "INSERT INTO reg_units(regId, exam_unit_id) VALUES($editRegId, $unitId)";
                                        $reg_units_query = mysqli_query($con, $reg_units_sql);

                                        if (!$reg_units_query) {
                                            $inserted = false;
                                            break;
                                        }
                                    }
                                    if ($inserted) {
                                        header("Location: index.php?success=Exam registration successfully edited.");
                                        exit();
                                    } else {
                                        header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                        exit();
                                    }
                                }else {
                                    header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                    exit();
                                }
                            } else {
                                header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                exit();
                            }
                        }else {
                            $sql = "SELECT * FROM stud_exam_reg WHERE stud_regNo = '$regNo' AND level = '$level' AND type = '$type' AND exam_id = $exam_id";

                            $result = mysqli_query($con, $sql);

                            if (!$result) {
                                die("Query failed: " . mysqli_error($con));
                            }

                            if (mysqli_num_rows($result) == 0) {
                                $stud_exam_reg_sql = "INSERT INTO stud_exam_reg(exam_id, stud_regNo, level, combId, type, reg_date) VALUES($exam_id, '$regNo', $level, $combination, '$type', '$date')";
                                $stud_exam_reg_query = mysqli_query($con, $stud_exam_reg_sql);

                        if (!$stud_exam_reg_query) {
                            header("Location: index.php?error=Something-went-wrong");
                            exit();
                        }

                                $regId = mysqli_insert_id($con);
                                $inserted = true;

                                foreach ($regUnits as $unitId) {
                                    $reg_units_sql = "INSERT INTO reg_units(regId, exam_unit_id) VALUES($regId, $unitId)";
                                    $reg_units_query = mysqli_query($con, $reg_units_sql);

                                    if (!$reg_units_query) {
                                        $inserted = false;
                                        break;
                                    }
                                }

                                if ($inserted) {
                                    header("location: index.php?success=Successfully Registered.");
                                } else {
                                    header("Location: index.php?error=Something-went-wrong");
                                }
                            } else {
                                header("Location: index.php?error=You are already registered for the same level, type, and exam.<br>You can edit your existing registration through the menu");

                            }



                        }

                        displayStep3();
                        // if ($type === "repeat") {
                        // }
                    }
                }

                function processStep3() {
                    global $con, $exam, $regNo;
                    if(isset($_FILES["slipFile"]["name"]) and $_FILES["slipFile"]["name"] != Null){
                        $path = $_FILES['slipFile']['name'];
                        $ext_slipFile = pathinfo($path, PATHINFO_EXTENSION);
                    }

                    if(isset($_FILES["approvalFile"]["name"]) and $_FILES["approvalFile"]["name"] != Null) {
                        $path = $_FILES['approvalFile']['name'];
                        $ext_approvalFile = pathinfo($path, PATHINFO_EXTENSION);
                    }

                    if(strtolower($ext_slipFile) != "pdf" or (isset($ext_approvalFile) and strtolower($ext_approvalFile) != "pdf")){
                        global $slip_msg;
                        $slip_msg = "Upload only pdf file!";
                        displayStep3();
                    }
                    else if (isset($_POST["submit"]) and $_POST["submit"] == "< Back") {

                        $exam_id = $exam['exam_id'];
                        $type = $_POST['type'];
                        $level = $_POST['level'];
                        $combination = $_POST['combination'];

                        $unitSQL = "
                        SELECT DISTINCT u.unitId, u.unitCode, u.name
                        FROM unit u
                        INNER JOIN combination_subjects cs ON u.subject = cs.subject
                        INNER JOIN unit_sub_exam usexam ON u.unitId = usexam.unitId
                        WHERE cs.combinationID = $combination
                        AND u.level = $level
                        AND usexam.exam_id = $exam_id
                        AND usexam.type = '$type';
                    ";

                        $unitsQueryResult = mysqli_query($con, $unitSQL);
                        if ($unitsQueryResult) {
                            if (mysqli_num_rows($unitsQueryResult) == 0) {
                                header("Location: index.php?error=No units were assign to this combination.");
                                exit();
                            }
                        } else {
                            header("Location: index.php?error=Something-went-wrong");
                            exit();
                        }
                        displayStep2($unitsQueryResult);

                    }else if(isset($_POST["submit"]) and $_POST["submit"] == "Submit") {
                        global $con, $exam, $regNo;
                        $exam_id = $exam['exam_id'];
                        $type = $_POST['type'];
                        $level = $_POST['level'];
                        $editRegId = (isset($_POST['regId']))?$_POST['regId']:false;
                        $combination = $_POST['combination'];
                        $regUnits = $_POST['units'];
                        $date =date('Y-m-d');

                        if($editRegId){

                            $updateQuery = "UPDATE stud_exam_reg SET
                                                type = '$type',
                                                level = $level,
                                                combId = $combination,
                                                reg_date = '$date'
                                                WHERE regId = $editRegId";

                            $payslipName ="None";
                            if(isset($_FILES["slipFile"]["name"]) and $_FILES["slipFile"]["name"] != Null){
                                $src = $_FILES["slipFile"]["tmp_name"];
                                $path = $_FILES['slipFile']['name'];
                                $ext = pathinfo($path, PATHINFO_EXTENSION);
                                $payslipName = str_replace("/","",$regNo)."_".$editRegId."_payment_slip".".".$ext;
                                $target = "../assets/uploads/repeat_slips/payment_slips/" . $payslipName;
                                move_uploaded_file($src, $target);
                            }
                            $senateLetterName ="None";
                            if(isset($_FILES["approvalFile"]["name"]) and $_FILES["approvalFile"]["name"] != Null){
                                $src = $_FILES["approvalFile"]["tmp_name"];
                                $path = $_FILES['approvalFile']['name'];
                                $ext = pathinfo($path, PATHINFO_EXTENSION);
                                $senateLetterName = str_replace("/","",$regNo)."_".$editRegId."_senate_approval_letter".".".$ext;
                                $target = "../assets/uploads/repeat_slips/senate_approval_letter/" . $senateLetterName;
                                move_uploaded_file($src, $target);
                            }

                            $slip_sql = "UPDATE repeat_slips SET payment_slip = '$payslipName', senate_approval_letter ='$senateLetterName', payment_slip_status = 'pending' , senate_approval_letter_status = 'pending' 
                                  WHERE regId = $editRegId";
                            $slip_sql_query = mysqli_query($con, $slip_sql);

                            if (mysqli_query($con, $updateQuery)) {

                                $deleteQuery = "DELETE FROM reg_units WHERE regId = $editRegId";
                                if (mysqli_query($con, $deleteQuery)) {
                                    $inserted = true;
                                    foreach ($regUnits as $unitId) {
                                        $reg_units_sql = "INSERT INTO reg_units(regId, exam_unit_id) VALUES($editRegId, $unitId)";
                                        $reg_units_query = mysqli_query($con, $reg_units_sql);

                                        if (!$reg_units_query) {
                                            $inserted = false;
                                            break;
                                        }
                                    }
                                    if ($inserted) {
                                        header("Location: index.php?success=Exam registration successfully edited.");
                                        exit();
                                    } else {
                                        header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                        exit();
                                    }
                                }else {
                                    header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                    exit();
                                }
                            } else {
                                header("Location: index.php?error=Exam registration editing failed. Please try again.");
                                exit();
                            }
                        }else {
                            $sql = "SELECT * FROM stud_exam_reg WHERE stud_regNo = '$regNo' AND level = '$level' AND type = '$type' AND exam_id = $exam_id";

                            $result = mysqli_query($con, $sql);

                            if (!$result) {
                                die("Query failed: " . mysqli_error($con));
                            }

                            if (mysqli_num_rows($result) == 0) {
                                $stud_exam_reg_sql = "INSERT INTO stud_exam_reg(exam_id, stud_regNo, level, combId, type, reg_date) VALUES($exam_id, '$regNo', $level, $combination, '$type', '$date')";
                                $stud_exam_reg_query = mysqli_query($con, $stud_exam_reg_sql);

                                if (!$stud_exam_reg_query) {
                                    header("Location: index.php?error=Something-went-wrong");
                                    exit();
                                }

                                $regId = mysqli_insert_id($con);
                                $inserted = true;

                                foreach ($regUnits as $unitId) {
                                    $reg_units_sql = "INSERT INTO reg_units(regId, exam_unit_id) VALUES($regId, $unitId)";
                                    $reg_units_query = mysqli_query($con, $reg_units_sql);

                                    if (!$reg_units_query) {
                                        $inserted = false;
                                        break;
                                    }
                                }
                                $payslipName ="None";
                                if(isset($_FILES["slipFile"]["name"]) and $_FILES["slipFile"]["name"] != Null){
                                    $src = $_FILES["slipFile"]["tmp_name"];
                                    $path = $_FILES['slipFile']['name'];
                                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                                    $payslipName = str_replace("/","",$regNo)."_".$regId."_payment_slip".".".$ext;
                                    $target = "../assets/uploads/repeat_slips/payment_slips/" . $payslipName;
                                    move_uploaded_file($src, $target);
                                }
                                $senateLetterName ="None";
                                if(isset($_FILES["approvalFile"]["name"]) and $_FILES["approvalFile"]["name"] != Null){
                                    $src = $_FILES["approvalFile"]["tmp_name"];
                                    $path = $_FILES['approvalFile']['name'];
                                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                                    $senateLetterName = str_replace("/","",$regNo)."_".$regId."_senate_approval_letter".".".$ext;
                                    $target = "../assets/uploads/repeat_slips/senate_approval_letter/" . $senateLetterName;
                                    move_uploaded_file($src, $target);
                                }

                                $slip_sql = "INSERT INTO repeat_slips(regId, payment_slip, senate_approval_letter) VALUES($regId, '$payslipName','$senateLetterName')";
                                $slip_sql_query = mysqli_query($con, $slip_sql);

                                if (!$slip_sql_query) {
                                    $inserted = false;
                                }

                                if ($inserted) {
                                    header("location: index.php?success=Successfully Registered.");
                                } else {
                                    header("Location: index.php?error=Something-went-wrong");
                                }
                            } else {
                                header("Location: index.php?error=You are already registered for the same level, type, and exam.<br>You can edit your existing registration through the menu");

                            }



                        }
                    }

                }

                function displayStep1() {
                    global $combinationList, $_POST, $examUnitId, $exam, $regNo, $indexNo, $edit;
                    if(isset($_POST['regId']) AND !isset($_POST['level'])){
                        $_POST['level'] = $GLOBALS['regDetail']['level'];
                        $_POST['type'] = $GLOBALS['regDetail']['type'];
                    }

                    if(isset($_POST['units']))
                        $selectedUnits = $_POST['units'];
                    else if($GLOBALS['edit']){
                        $selectedUnits = $examUnitId;
                    }else
                        $selectedUnits = array();
                    ?>
                    <h1 class="text-lg font-black text-center underline mt-5 text-gray-800 lg:text-2xl bg-white text-black dark:bg-gray-800 dark:text-white">Exam Registration</h1>

                    <div class="instructions mt-7 mb-16 bg-white text-black dark:bg-gray-800 dark:text-white">
                        <p class="font-bold">Read the following instructions carefully before filling this form ------ &gt; &gt; &gt;</p>
                        <ol class="ml-4 lg:ml-7 my-4 list-decimal text-justify">
                            <li>Students are advised to use either an individual smartphone or personal computer to avoid technical errors.</li>
                            <li>Only one record will be accepted per email ID</li>
                            <li>If you provide any incorrect information, you will be barred from sitting the examination</li>
                        </ol>
                        <hr>
                        <br>
                        <p class="ml-0 lg:ml-2 font-semibold">The following instructions are for the repeat candidates</p>
                        <ol class="ml-4 lg:ml-7 my-4 list-decimal text-justify space-y-4">
                            <li>
                                Candidates who are repeating the exam  can pay the exam fees using the <b>normal Deposit Slip</b> available in the <b><u>Peoples Bank</u>. Reference No/Account No - 480000022100084</b>
                                <br><br><b>Exam Fees:</b>
                                <ul class="ml-4 lg:ml-7 list-decimal text-justify">
                                    <li class="font-semibold">Theory and Practical of a course unit - Rs. 250/=</li>
                                    <li class="font-semibold">Theory or Practical of a course unit - Rs. 250/=</li>
                                </ul>
                                <br>
                                <b class="font-bold"><u>NOTE:</u></b><br>
                                <ul class="ml-4 lg:ml-7 my-1 list-decimal text-justify">
                                    <li class="font-semibold">Please write the following details in the back side of the Deposit Slip: <br> 1. Full Name  2. Reg. No.   3. Level of Repeat Exam   4. Applied course units</li>
                                    <li class="font-semibold">You need to include both sides of the Deposit Slip in a single PDF file and attach that PDF file. (Only one PDF file is allowed)</li>
                                    <li class="font-semibold">Original Deposit Slip should be handover to the Dean's office</li>
                                </ul>
                            </li>
                            <li>Those who had submitted Medical  Certificate for not sitting the ECEs last year, should also pay Rs. 250/- per course unit</li>
                            <li>Original Payment Voucher should be submitted to the Dean's Office </li>
                        </ol>

                        <p>If you have any questions, feel free to contact us at using contact us option in this website.</p>
                        <p class="text-right mt-5">Assistant Registrar<br>FACULTY OF SCIENCE</p>
                    </div>
                    <div class="w-11/12 mx-auto bg-white text-black dark:bg-gray-800 dark:text-white">
                        <h3 class="font-bold lg:text-xl text-center text-gray-800">Personal Details</h3>
                        <form action="exam_reg.php" method="POST" class="mt-10 h-[350px] flex flex-col justify-around dark:bg-gray-800 dark:text-white" id="examForm">
                            <input type="hidden" name="step" value="1" />
                            <?php if(isset($_POST['regId'])) {
                                echo "<input type='hiddestepn' name='regId' value='" . $_POST['regId'] . "' />\n";
                                echo "<input type='hidden' name='level' value='" . $_POST['level'] . "' />\n";
                                echo "<input type='hidden' name='type' value='" . $_POST['type'] . "' />\n";
                            }
                            ?>
                            <?php foreach ($selectedUnits as $unitId) { ?>
                                <input type="hidden" name="units[]" value="<?php echo $unitId; ?>" />
                            <?php } ?>
                            <div class="detail-row  my-1 !block lg:!grid !w-full">
                                <label class="hidden lg:block " for="indexNo">Index Number: <span class="text-red-500">*</span></label>
                                <input class="inputs tracking-wider disabled:opacity-60 bg-white text-black dark:bg-gray-800 dark:text-white" type="text" name="indexNo" value="<?php echo $indexNo ?>" disabled />
                            </div>
                            <div class="detail-row  my-1 !block lg:!grid !w-full">
                                <label class="hidden lg:block bg-white text-black dark:bg-gray-800 dark:text-white" for="type">Type: <span class="text-red-500">*</span></label>
                                <select class="inputs bg-white text-black dark:bg-gray-800 dark:text-white" id="type" name="type"  required  <?php if(isset($_POST['regId'])) echo "disabled";?>>
                                    <option value="select" <?php setSelected('type', 'select') ?> disabled selected>Select Type</option>
                                    <?php
                                    global $exams_type;
                                    foreach ($exams_type as $type => $val){
                                        if(count($val))
                                            echo "<option value='$type' "; setSelected('type', $type); echo ">".ucfirst($type)."</option>";
                                    } ?>
                                </select>
                            </div>
                            <div id="level_block" class="detail-row  my-1 !block lg:!grid !w-full">

                                <label class="hidden lg:block " for="level">Level: <span class="text-red-500">*</span></label>
                                <select class="inputs bg-white text-black dark:bg-gray-800 dark:text-white" id="level" name="level" required <?php if(isset($_POST['regId'])) echo "disabled";?>>
                                    <option value="select" <?php setSelected('level', 'select') ?> disabled selected>Select Level</option>
                                    <?php
                                    global $exams_type;
                                    if(isset($_POST['type']))
                                        foreach ($exams_type[$_POST['type']] as $val){
                                                echo "<option value='$val' "; setSelected('level', $val); echo ">Level ".$val."</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <script>
                                var examsType = <?php global $exams_type; echo json_encode($exams_type); ?>;

                                var typeDropdown = document.getElementById("type");
                                var levelDropdown = document.getElementById("level");
                                const levelDropdownBlock = document.getElementById("level_block");
                                if(typeDropdown.value == "select"){
                                    levelDropdown.disabled = true;
                                }

                                typeDropdown.addEventListener("change", function () {
                                    var selectedType = typeDropdown.value;
                                    var levelOptions = examsType[selectedType];
                                    levelDropdown.disabled = false;

                                    // Clear existing options
                                    while (levelDropdown.options.length > 1) {
                                        levelDropdown.remove(1);
                                    }

                                    // Add new options based on the selected type
                                    for (var i = 0; i < levelOptions.length; i++) {
                                        var option = document.createElement("option");
                                        option.value = levelOptions[i];
                                        option.text = "Level " + levelOptions[i];
                                        levelDropdown.appendChild(option);
                                    }
                                });
                            </script>

                            <div class="detail-row  my-1 !block lg:!grid !w-full">
                                <label class="hidden lg:block" for="combination">Subject Combination: <span class="text-red-500">*</span></label>
                                <select class="inputs bg-white text-black dark:bg-gray-800 dark:text-white" id="combination" name="combination" required>
                                    <option value="select" disabled selected>Select Combination</option>
                                    <?php
                                    while ($userCombination = mysqli_fetch_assoc($combinationList)) { ?>
                                        <option value="<?php echo $userCombination['combinationID'] ?>" <?php setSelected('combination', $userCombination['combinationID']) ?>>
                                            <?php echo $userCombination['combinationName']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- <input type="checkbox" name="units[]" id="units" value > -->

                            <input class="btn fill-btn mt-8 " type="submit" name="submit" value="Next &gt;" />

                        </form>
                    </div>

                <?php }

                function displayStep2($unitsQueryResult) {
                    $selectedUnits = (isset($_POST['units']))?$_POST['units']:array();
                    $count = 0;
                    ?>
                    <div class="w-full lg:w-11/12 mx-auto bg-white text-black dark:bg-gray-800 dark:text-white">
                        <div>
                            <h3 class="font-bold lg:text-xl text-center text-gray-800">Select Units</h3>
                            <p class="text-center text-gray-500">Select course units you want to apply for the exam. If any course units need to be added, please contact the respective Heads of departments.</p>
                        </div>
                        <form action="exam_reg.php" method="POST" class="mt-10 min-h-[350px] w-11/12 lg:w-3/4 mx-auto flex flex-col gap-y-5">
                            <input type="hidden" name="step" value="2" />
                            <?php if(isset($_POST['regId'])) echo "<input type='hidden' name='regId' value='".$_POST['regId']."' />" ?>

                            <select id="type" name="type" hidden>
                                <option value="select" <?php setSelected('type', 'select') ?>>Select Type</option>
                                <option value="proper" <?php setSelected('type', 'proper') ?>>Proper</option>
                                <option value="repeat" <?php setSelected('type', 'repeat') ?>>Repeat</option>
                            </select>
                            <select id="level" name="level" hidden>
                                <option value="select" <?php setSelected('level', 'select') ?>>Select Level</option>
                                <option value="1" <?php setSelected('level', 1) ?>>Level 1</option>
                                <option value="2" <?php setSelected('level', 2) ?>>Level 2</option>
                                <option value="3" <?php setSelected('level', 3) ?>>Level 3</option>
                                <option value="4" <?php setSelected('level', 4) ?>>Level 4</option>
                            </select>

                            <input type="hidden" value="<?php echo $_POST['combination'] ?>" name="combination">


                            <?php while ($unit = mysqli_fetch_assoc($unitsQueryResult)) {
                                $count++;
                                $unitId = $unit['unitId'];
                                $isChecked = in_array($unitId, $selectedUnits);
                                ?>
                                <div class="grid grid-cols-3 content-center">
                                    <label class="font-[400] col-span-2" for="<?php echo "unit_$count" ?>"><?php echo $unit['name'] ?></label>
                                    <input class="border-blue-500 w-5 h-5 justify-self-end self-center" type="checkbox" name="units[]" value="<?php echo $unitId ?>" id="<?php echo "unit_$count" ?>" <?php if ($isChecked) echo "checked"; ?> />
                                </div>
                                <?php
                            }
                            ?>

                            <div class="w-full flex items-center justify-around mt-5">
                                <input class="btn outline-btn w-5/12" type="submit" name="submit" value="&lt; Back" />
                                <input class="btn fill-btn w-5/12" type="submit" name="submit" value="<?php echo ($_POST['type'] == 'repeat')?'Next &gt;':'Submit'?>" />
                            </div>
                        </form>
                    </div>
                <?php }


                function displayStep3() {
                    global $examUnitId, $_POST, $slip_msg;
                    if(isset($_POST['units']))
                        $selectedUnits = $_POST['units'];
                    else if($GLOBALS['edit']){
                        $selectedUnits = $examUnitId;
                    }else
                        $selectedUnits = array();
                    ?>

                    <div class="mx-auto w-11/12">
                        <div class="text-center">
                            <h3 class="font-bold lg:text-xl text-gray-800">Payment Slip copies</h3>
                            <p class="text-gray-500 mt-1">Read and understand the following instructions</p>
                            <ol class="ml-4 lg:ml-7 my-4 list-decimal text-justify space-y-4">
                                <li>
                                Upload the soft copies of payment slip. File type should be <span class="font-semibold">PDF</span> and it should <span class="font-semibold">include both side of the slip</span>.
                                    <b class="font-bold"><u>NOTE:</u></b><br>
                                    <ul class="ml-4 lg:ml-7 my-1 list-decimal text-justify">
                                        <li class="font-semibold">Please write the following details in the back side of the Deposit Slip: <br> 1. Full Name  2. Reg. No.   3. Level of Repeat Exam   4. Applied course units</li>
                                        <li class="font-semibold">You need to include both sides of the Deposit Slip in a single PDF file and attach that PDF file. (Only one PDF file is allowed)</li>
                                        <li class="font-semibold">Original Deposit Slip should be handover to the Dean's office</li>
                                    </ul>
                                </li>
                                <li>Those who had submitted Medical Certificate for not sitting the ECEs last year, should also pay Rs. 250/- per course unit and submit both slip copies and senate approval letter.</li>
                                <li>Original Payment Voucher and senate approval letter (Optional) should be submitted to the Dean's Office </li>
                            </ol>
                        </div>
                        <?php if (isset($slip_msg)) : ?>
                            <div class="text-center error-msg text-red-500"><?php echo $slip_msg; ?></div>
                        <?php endif; ?>
                        <form action="exam_reg.php" method="POST" enctype="multipart/form-data" class="mt-10 mb-5 w-3/4 mx-auto flex flex-col gap-y-8">
                            <input type="hidden" name="step" value="3" />
                            <?php if(isset($_POST['regId'])) echo "<input type='hidden' name='regId' value='".$_POST['regId']."' />" ?>
                            <?php foreach ($selectedUnits as $unitId) { ?>
                                <input type="hidden" name="units[]" value="<?php echo $unitId; ?>" />
                            <?php } ?>
                            <input type="hidden" value="<?php echo $_POST['combination'] ?>" name="combination">
                            <select id="type" name="type" hidden>
                                <option value="select" <?php setSelected('type', 'select') ?>>Select Type</option>
                                <option value="proper" <?php setSelected('type', 'proper') ?>>Proper</option>
                                <option value="repeat" <?php setSelected('type', 'repeat') ?>>Repeat</option>
                            </select>
                            <select id="level" name="level" hidden>
                                <option value="select" <?php setSelected('level', 'select') ?>>Select Level</option>
                                <option value="1" <?php setSelected('level', 1) ?>>Level 1</option>
                                <option value="2" <?php setSelected('level', 2) ?>>Level 2</option>
                                <option value="3" <?php setSelected('level', 3) ?>>Level 3</option>
                                <option value="4" <?php setSelected('level', 4) ?>>Level 4</option>
                            </select>

                            <div class="w-full flex gap-x-5 items-center justify-between">
                                <label for="regno">Payment Slips: </label>
                                <input type="file" class="w-full h-full file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-[#5465ff] hover:file:bg-violet-100" name="slipFile" accept=".pdf" required>
                            </div>
                            <div class="w-full flex gap-x-5 items-center justify-between">
                                <label for="regno">Senate approval letter: </label>
                                <input type="file" class="w-full h-full file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-[#5465ff] hover:file:bg-violet-100" name="approvalFile" accept=".pdf">
                            </div>
                            <div class="w-full flex items-center justify-around mt-5">
                                <input class="btn outline-btn w-5/12" type="submit" name="submit" value="&lt; Back" />
                                <input class="btn fill-btn w-5/12" type="submit" name="submit" value="Submit" />
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    const userMenu = document.getElementById('user-menu');

    function openMenu() {
        userMenu.classList.toggle('hidden');
        userMenu.classList.toggle('absolute');
        userMenu.classList.toggle('-translate-y-full');
        userMenu.classList.toggle('lg:translate-x-full');
    }
</script>
<?php if(!(isset($_POST['step']) && $_POST['step']==1)){ ?>
<script>
    // Get form and form elements
    var examForm = document.getElementById("examForm");
    var indexNoInput = document.getElementsByName("indexNo")[0];
    var typeSelect = document.getElementsByName("type")[0];
    var levelSelect = document.getElementsByName("level")[0];
    var combinationSelect = document.getElementsByName("combination")[0];

    // Add event listener for form submission
    examForm.addEventListener("submit", function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // Validation function
    function validateForm() {
        var indexNo = indexNoInput.value;
        var type = typeSelect.value;
        var level = levelSelect.value;
        var combination = combinationSelect.value;

        // Add your validation logic here
        if (indexNo === "") {
            alert("Index Number is required.");
            return false;
        }
        if (type === "select") {
            alert("Please select a Type.");
            return false;
        }
        if (level === "select") {
            alert("Please select a Level.");
            return false;
        }
        if (combination === "select") {
            alert("Please select a Combination.");
            return false;
        }

        // If all validations pass, return true
        return true;
    }
</script>

<?php } ?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>