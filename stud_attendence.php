<?php
ob_start();

if (!isset($_SESSION['role'])) {
    header("location:../login.php");
    exit();
}

include("../config/connect.php");

if (isset($_POST['submit'])) {
    $regno = strtoupper($_POST['regno']);
    $regNoPattern = '/^\d{4}\/[A-Z]+\/\d{3}$/';

    // Check if the registration number format is valid
    if (!preg_match($regNoPattern, $regno)) {
        $msg = ["Invalid Registration No (XXXX/XXX/XXX)", "text-red-500"];
    } else {
        // Prepare a statement to check if regNo already exists
        $stmt = $con->prepare("SELECT * FROM student_attendence WHERE regNo = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Registration number already exists
            $msg = ["", "text-green-500"];
        } else {
            // Registration number does not exist, you can proceed with the insert
            $msg = ["Registration Number doesn't exists", "text-red-500"];
            // Insert logic goes here
        }

        // Close the statement
        $stmt->close();
    }
}



// Fetch allocated units for the student
$student_sub_combination = "SELECT regNO , unit_code, level , attendence FROM student_attendence  WHERE regNO = ? ";

$stmt = $con->prepare($student_sub_combination);
$stmt->bind_param("s", $regno);
$stmt->execute();
$Student_Attendence_Result = $stmt->get_result();
?>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<div class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title mb-5">Add Attendance</h1>
    <form action="" method="post" class="w-full flex flex-col items-center gap-5">
    <?php
        if (isset($msg)) {
            echo "<b class='" . $msg[1] . "'>" . $msg[0] . "</b>";
        }
    ?>

    <div class="w-full grid grid-cols-3 items-center h-10">
        <label for="regno">Registration No : </label>
        <input type="text" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="regno" required>
    </div>

    <?php if ($Student_Attendence_Result->num_rows > 0): ?>
        <h3 class="text-base font-bold">Registration Number :  <?php echo $regno; ?></h3>
        <table class="w-[500px]" border='2'>
            <tr class="h-12 bg-blue-100 font-semibold">
                <th>Level</th>
                <th>Unit Code</th>
                <th>Attendance</th>
            </tr>
            
            <?php while ($user = $Student_Attendence_Result->fetch_assoc()): ?>
                <tr class="h-12 odd:bg-blue-50">                    
                    <td class=" text-center"><?php echo $user['level']; ?></td>
                    <td class=" text-center"><?php echo $user['unit_code']; ?></td>
                    <td class=" text-center"><?php echo $user['attendence']; ?>%</td>
                </tr>
            <?php endwhile; ?>
        
        </table>
    <?php endif; ?>

    <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-2">
        <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
        <input type="submit" class="col-span-2 w-full btn fill-btn" name="submit" value="View Attendence">
    </div>
    <a href="index.php?page=attd_bulk" class="w-full btn outline-btn !text-green-600 !border-green-500 !bg-white hover:!bg-green-500 hover:!text-white">Bulk Upload</a>
    </form>
</div>
