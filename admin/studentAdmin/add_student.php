<?php
ob_start();
if (!isset($_SESSION['role'])) {
    header("location:../login.php");
    exit();
}


include("../config/connect.php");
if (isset($_POST['submit'])) {
    $email = strtolower($_POST['email']);
    $regno = strtoupper($_POST['regno']);

    // Check the name validation
    $regNoPattern = '/^\d{4}\/[A-Z]+\/\d{3}$/';
    if (!preg_match($regNoPattern, $regno)) {
        $msg[0] = "Invalid Registration No (XXXX/XXX/XXX)";
        $msg[1] = "text-red-500";
    } else {
        $query = "SELECT * from student_check where regNo ='$regno' or email ='$email'";

        if (mysqli_num_rows(mysqli_query($con, $query))) {

            $msg[0] = "registration no or email already added!";
            $msg[1] = "text-red-500";
        } else {
            $query = "INSERT INTO student_check (regNo,email) values('$regno','$email')";
            if (!mysqli_query($con, $query)) {

                $msg[0] = "error!";
                $msg[1] = "text-red-500";
            } else {
                $query = "INSERT INTO student (regNo) values('$regno')";
                mysqli_query($con, $query);
                $msg[0] = "Successfully added!";
                $msg[1] = "text-green-500";
            }
        }
    }


}


?>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>


<div class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title mb-5">Add Student</h1>
    <form action="" method="post" class="w-full flex flex-col items-center gap-5">
        <?php
        if (isset($msg)) {
            echo "<b class='" . $msg[1] . "'>" . $msg[0] . "</b>";
        }
        ?>


        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="regno">Registration No.: </label>
            <input type="text" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="regno" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="email">Email: </label>
            <input type="email" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="email" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-2">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" class="col-span-2 w-full btn fill-btn" name="submit" value="Register">
        </div>
        <a href="index.php?page=bulk" class="w-full btn outline-btn !text-green-600 !border-green-500 !bg-white hover:!bg-green-500 hover:!text-white">Bulk Upload</a>
    </form>
</div>