<?php
ob_start();
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:../index.php");
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
include("../config/connect.php");
$regNo = $_SESSION['userid'];
$selectSQL = "SELECT * FROM student WHERE regNo = '$regNo';";
$selectQuery = mysqli_query($con, $selectSQL);
$user = mysqli_fetch_assoc($selectQuery);
$profile_img = isset($user['profile_img']) ? $user['profile_img'] : "blankProfile.png";
$form ="";
if(isset($_POST['regId'])) {
    $regId = $_POST['regId'];

    $sql = "SELECT * FROM stud_exam_reg WHERE `regId` = $regId";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $level = $row['level'];
        $type = $row['type'];
        $examID = $row['exam_id'];
        $form = "DisplayList";
    } else {
        header("Location: index.php?error=No registration found with regId: $regId");
        exit();
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
<body class="bg-slate-200" id="exam">
<?php if (isset($_GET['error'])) { ?>
    <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
        <form class="card h-40 w-1/2 flex flex-col items-center justify-around gap-7" action="index.php" method="POST">
            <p class="text-center"><?php echo $_GET['error'] ?></p>
            <input class="btn fill-btn" type="submit" value="OK" name="ok">
        </form>
    </div>
<?php } elseif (isset($_GET['success'])) { ?>
    <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
        <form class="card h-40 w-1/2 flex flex-col items-center justify-around gap-7" action="index.php" method="POST">
            <p class="text-center text-green-700"><?php echo $_GET['success'] ?></p>
            <input class="btn fill-btn !bg-green-700" type="submit" value="OK" name="ok">
        </form>
    </div>
<?php } ?>


<nav class="w-full h-[15vh] min-h-fit drop-shadow-md bg-white fixed top-0 left-0 z-10">
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
    <div class="hidden top-[14.8vh] right-0 h-56 w-full bg-white -translate-y-full z-20 transition-transform lg:top-[16vh] lg:drop-shadow-2xl lg:right-24 lg:w-56 lg:translate-x-full lg:h-72 lg:rounded-tl-3xl lg:rounded-br-3xl lg:text-gray-800" id="user-menu">
        <ul class="w-11/12 h-full m-auto flex flex-col items-center justify-around text-center">
            <li class="mt-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="exam_reg.php">Exam Registration</a></li>
            <div class="h-px w-3/4 bg-gray-300"></div>
            <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="index.php">Dashboard</a></li>
            <div class="h-px w-3/4 bg-gray-300"></div>
            <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="../contact.html">Contact</a></li>
            <div class="h-px w-3/4 bg-gray-300"></div>
            <li class="mb-3 "><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
<div class="body-sec my-[20vh]">
    <div class="container m-auto">
        <div class="card w-11/12 m-auto overflow-x-auto overflow-y-auto ">

        <?php include("../registrationList/reg_list.php") ?>
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
