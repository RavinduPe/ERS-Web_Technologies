<?php
ob_start();
if (!isset($_SESSION['role'])) {
    header("location:../login.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . '/config/connect.php');
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

if (isset($_POST['upload'])) {
    $email = $_POST['email'];
    $regno = $_POST['regno'];
    $excel_file = $_FILES['excelFile']['name'];
    $extension = pathinfo($excel_file, PATHINFO_EXTENSION);
    if ($extension == 'csv') {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else if ($extension == 'xls') {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }
    $spreadsheet = $reader->load($_FILES['excelFile']['tmp_name']);
    $sheetdata = $spreadsheet->getActiveSheet()->toArray();
    $dataRowCount = count($sheetdata);
    $dataColCount = count($sheetdata[0]);
    $regnoIndex = 0;
    $emailIndex = 0;
    for ($i=0; $i < $dataColCount; $i++) {
        if ($sheetdata[0][$i] === $regno) {
            $regnoIndex = $i;
        } else if ($sheetdata[0][$i] === $email) {
            $emailIndex = $i;
        }
    }
    if ($dataRowCount > 1) {
        $data = array();
        for ($i=1; $i < $dataRowCount; $i++) {
            $regNo = $sheetdata[$i][$regnoIndex];
            $email = $sheetdata[$i][$emailIndex];
            $data[] = array(
                'regNo'=> $regNo,
                'email'=> $email,
            );
        }
    }
    $msgs["Invalid Registration No (XXXX/XXX/XXX)"] =0;
    $msgs["Registration No or email already added!"] =0;
    $msgs["Successfully added!"] =0;
    $msgs["error!"]=0;


    foreach ($data as $user) {
        $regNo = $user['regNo'];
        $email = $user['email'];

        // Check the name validation
        $regNoPattern = '/^\d{4}\/[A-Z]+\/\d{3}$/';
        if (!preg_match($regNoPattern, $regNo)) {
            $msgs["Invalid Registration No (XXXX/XXX/XXX)"]++;
        } else {
            $query = "SELECT * from student_check where regNo ='$regNo' or email ='$email'";
    
            if (mysqli_num_rows(mysqli_query($con, $query))) {

                $msgs["Registration No or email already added!"]++;
            } else {
                $query = "INSERT INTO student_check (regNo,email) values('$regNo','$email')";
                if (!mysqli_query($con, $query)) {
                    $msgs["error!"]++;
                } else {
                    $query = "INSERT INTO student (regNo) values('$regNo')";
                    mysqli_query($con, $query);
                    $msgs["Successfully added!"]++;
                }
            }
        }        
    }


}


?>

<div class="w-[500px] mx-auto flex flex-col items-center gap-4">
    <h1 class="title mb-2">Add Student (bulk Upload)</h1>
    <p class="mb-5 text-center tracking-wider font-normal">Please add the relevant column names for the registration number and email.</p>
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
            <label for="regno">Excel File: </label>
            <input type="file" class="col-span-2 w-full h-full file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-[#5465ff] hover:file:bg-violet-100" name="excelFile" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="regno">Registration No.: </label>
            <input type="text" placeholder="Excel files' relevant column name" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="regno" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="email">Email: </label>
            <input type="text" placeholder="Excel files' relevant column name" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="email" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-2">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" class="col-span-2 w-full btn fill-btn" name="upload" value="Upload">
        </div>
    </form>
</div>


<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>