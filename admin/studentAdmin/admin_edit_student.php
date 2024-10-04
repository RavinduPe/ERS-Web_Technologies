<script>
    function view(regNo) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=viewStud";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement("input");
        inp.name = "regNo";
        inp.value = regNo;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
    }
</script>
<?php
$msg = array();

if (isset($_POST['regNo'])) {
    $regNo = $_POST['regNo'];
    $indexQuery = "";
    $editable = false;
    if (isset($exam)) {
        $examID = $exam['exam_id'];
        $indexSelect = ", exam_stud_index.indexNo ";
        $indexQuery = " LEFT JOIN `exam_stud_index` ON exam_stud_index.regNo = student.regNo AND `exam_id` = $examID";
        $editable = ($exam['status'] == "draft" or $exam['status'] == "registration") ? true : false;
    }

    $query = "SELECT student.*, student_check.*" . $indexSelect . " FROM student INNER JOIN student_check ON student.regNo = student_check.regNo " . $indexQuery . " WHERE student.regNo = '" . $regNo . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row['indexNo'] != "") {
        $indexNo = $row['indexNo'];
    } else {
        $indexNo = null;
    }

}


if (isset($_POST['save'])) {
    $newRegNo = $_POST["newRegNo"];
    $regNo = $_POST["regNo"];
    if (isset($exam)) $exam_id = $exam['exam_id'];
    if ($newRegNo != $regNo) {
        $test_new_regNo = $query = "SELECT * FROM student_check WHERE regNo = '" . $newRegNo . "'";
        $check_res = mysqli_query($con, $test_new_regNo);
        if ($check_res->num_rows != 0) {
            $msg['error'] = "Registration No already exists!";
        }
    }
    $email = $_POST["email"];
    $oldEmail = $row['email'];
    if ($email != $oldEmail) {
        $test_new_regNo = $query = "SELECT * FROM student_check WHERE email = '" . $email . "'";
        $check_res = mysqli_query($con, $test_new_regNo);
        if ($check_res->num_rows != 0) {
            $msg['error'] = "Email already exists!";
        }
    }

    if (count($msg) == 0) {
        $status = $_POST['status'];
        $indexNo = $_POST['indexNo'];
        $newindexNo = ($editable) ? $_POST['newindexNo'] : null;
        $fullName = $_POST["fullName"];
        $nameWithInitial = $_POST["nameWithInitial"];
        $district = $_POST["district"];
        $mobileNo = $_POST["mobileNo"];
        $landlineNo = $_POST["landlineNo"];
        $homeAddress = $_POST["homeAddress"];
        $addressInJaffna = $_POST["addressInJaffna"];

        $query = "UPDATE student INNER JOIN student_check ON student.regNo = student_check.regNo SET student_check.regNo = '$newRegNo', student_check.email = '$email', student_check.status = '$status', student.fullName = '$fullName', student.nameWithInitial = '$nameWithInitial', student.district = '$district', student.mobileNo = '$mobileNo', student.landlineNo = '$landlineNo', student.homeAddress = '$homeAddress', student.addressInJaffna = '$addressInJaffna' WHERE student.regNo = '" . $regNo . "'";
        $result = mysqli_query($con, $query);

        if ($result) {
            if ($editable and $indexNo != $newindexNo) {
                $test_new_indexNo = $query = "SELECT * FROM exam_stud_index WHERE indexNo = '" . $newindexNo . "'";
                $check_res = mysqli_query($con, $test_new_indexNo);
                if ($check_res->num_rows != 0) {
                    $msg['error'] = "Index No already exists!";
                } else {
                    if ($indexNo == null) {
                        $query = "INSERT INTO exam_stud_index(exam_id,regNo,indexNo) values('$exam_id','$newRegNo','$newindexNo')";
                        if (!mysqli_query($con, $query)) $msg['error'] = "index No update Failed";
                    } else {
                        if ($newindexNo == null)
                            $msg['error'] = "Index No cannot be empty!";
                        else {
                            $query = "UPDATE exam_stud_index SET indexNo ='$newindexNo' WHERE regNo = '$newRegNo' AND exam_id = $exam_id";
                            if (!mysqli_query($con, $query)) $msg['error'] = "index No update Failed";
                        }
                    }
                }
            }
        } else {
            $msg['error'] = "Connection Failed : " . mysqli_connect_error();
        }
    }
    if (!isset($msg['error'])) {
        mysqli_close($con);
        echo '<script> view("' . $newRegNo . '");</script>';
    }

}

?>

<style>
    .msg {
        margin-top: 10px;
        padding: 10px;
        border-radius: 3px;
    }

    .error-msg {
        background-color: #ffdddd;
        color: #ff0000;
    }
</style>


<div class="w-full mx-auto flex flex-col items-center gap-4">
    <h1 class="title">Edit student Profile</h1>
    <?php if (isset($msg['error'])) : ?>
        <div class="msg error-msg"><?php echo $msg['error']; ?></div>
    <?php endif; ?>
    <form method="post" action="" class="w-[500px] mx-auto flex flex-col items-center gap-4">
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="newRegNo">Registration No:</label>
            <input type="text" name="newRegNo" value="<?php echo $row['regNo']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
            <input type="hidden" name="regNo" value="<?php echo $row['regNo']; ?>"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="indexNo">Index No:</label>
            <input type="text" name="newindexNo" value="<?php echo ($indexNo) ? $indexNo : null; ?>"
                   placeholder="Index Number"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500 disabled:opacity-50" <?php echo ($editable) ? "" : "disabled" ?> />
            <input type="hidden" name="indexNo" value="<?php echo ($indexNo) ? $indexNo : null; ?>"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="status">Status:</label>
            <select for="status" name="status"
                    class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500">
                <option value="unregistered" <?php echo ("unregistered" == $row['status']) ? "selected" : ""; ?>>
                    unregistered
                </option>
                <option value="active" <?php echo ("active" == $row['status']) ? "selected" : ""; ?>>active</option>
                <option value="inactive" <?php echo ("inactive" == $row['status']) ? "selected" : ""; ?>>inactive
                </option>
            </select>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo $row['email']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="fullName">Full Name:</label>
            <input type="text" name="fullName" value="<?php echo $row['fullName']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="nameWithInitial">Name with Initials:</label>
            <input type="text" name="nameWithInitial" value="<?php echo $row['nameWithInitial']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="district">District:</label>
            <input type="text" name="district" value="<?php echo $row['district']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="mobileNo">Mobile No:</label>
            <input type="text" name="mobileNo" value="<?php echo $row['mobileNo']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="landlineNo">Home Tp No:</label>
            <input type="text" name="landlineNo" value="<?php echo $row['landlineNo']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="homeAddress">Home Address:</label>
            <input type="text" name="homeAddress" value="<?php echo $row['homeAddress']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="addressInJaffna">Address in Jaffna:</label>
            <input type="text" name="addressInJaffna" value="<?php echo $row['addressInJaffna']; ?>"
                   class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>

        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
            <button type="reset" name="reset" value="Reset" class="btn outline-btn">Reset</button>
            <button onclick="view('<?php echo $row['regNo']; ?>');return false" class="btn outline-btn">Discard</button>
            <button type="submit" name="save" value="Save" class="btn fill-btn">Save</button>
        </div>
    </form>
</div>


<!--<script>-->
<!--    if (window.history.replaceState) {-->
<!--        window.history.replaceState(null, null, window.location.href);-->
<!--    }-->
<!--</script>-->