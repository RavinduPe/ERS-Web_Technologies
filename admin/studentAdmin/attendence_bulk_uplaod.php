<?php
ob_start();
if (!isset($_SESSION['role'])) {
    header("location:../login.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/ERS-Web_Technologies/config/connect.php');
require($_SERVER['DOCUMENT_ROOT'] . '/ERS-Web_Technologies/vendor/autoload.php');

$uploadSuccess = false; // Initialize variable to track upload success
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jsonFile'])) {
    // Check if the file was uploaded without errors
    if ($_FILES['jsonFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['jsonFile']['tmp_name'];

        // Read and decode the JSON file
        $jsonContent = file_get_contents($fileTmpPath);
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            die('Error decoding JSON file');
        }
        
        // Loop through the "students" data
        foreach ($data['students'] as $student) {
            // echo "Registration Number: " . $student['regno'] . "<br>";
            // echo "Level: " . $student['level'] . "<br>";
            // echo "Courses:<br>";
            
            // Loop through each course for the student
            foreach ($student['cources'] as $course) {
                $mysql_regno_level = "INSERT INTO student_attendence(regNo,level,unit_code,attendence) VALUES('".$student['regno']."' , '".$student['level']."' , '".$course['uint_id']."' , '".$course['attendance']."') ";
                if (!mysqli_query($con, $mysql_regno_level)) {
                    $msgs["error!"]++;
                }
            }
        }
        $uploadSuccess = true; // Initialize variable to track upload success
        //echo '<p class="text-green-300 text-center text-xl font-bold">Successfully Uploaded</p>';

    } else {
        echo "Error: " . $_FILES['jsonFile']['error'];
    }
} 

?>





<div class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title mb-2">Add Attendence (Bulk Upload)</h1>
    <p class="mb-5 text-center tracking-wider font-normal">Please add the relevant column names for the registration number and code units.</p>
    <form action="" method="post" class="w-full flex flex-col items-center gap-5" enctype="multipart/form-data">
        <?php

        if (isset($msgs)) {
            foreach ($msgs as $msg => $val){
                $cls = "text-red-500";
                if($msg !="Successfully added!" && $val==0)
                    continue;
                if($msg =="Successfully added!")
                    $cls = "text-green-500";

                echo "<b class='" . $cls . "'>" . "$msg : $val". "</b>";
            }


        }
        ?>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="regno">JSON File: </label>
            <input type="file" class="col-span-2 w-full h-full file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-[#5465ff] hover:file:bg-violet-100" name="jsonFile" required>
        </div>
       
        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-2">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" class="col-span-2 w-full btn fill-btn" name="upload" value="Upload">
        </div>
    </form>
    <?php if ($uploadSuccess): ?>
        <p class="text-green-300 text-center text-xl font-bold mt-4">Successfully Uploaded</p>
    <?php endif; ?>
</div>


<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>