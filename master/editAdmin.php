<script>
    function view(adminId) {
        var myform = document.createElement("form");
        myform.action = "index.php?page=viewAdmin";
        myform.method = "post";
        myform.style.display = "none"; // Hide the form
        var inp = document.createElement('input');
        inp.name = "adminId";
        inp.value = adminId;
        inp.type = "hidden";
        myform.appendChild(inp);
        document.body.appendChild(myform);
        console.log(myform);
        myform.submit()
    }
</script>
<?php
$msg = array();
if (isset($_POST['editAdminId'])) {
    $adminId = $_POST['editAdminId'];
    $query = "SELECT *
FROM `admin` 
    LEFT JOIN `admin_details` ON `admin_details`.`email` = `admin`.`email` WHERE `admin`.`email` = '" . $adminId . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
}


if (isset($_POST['save'])) {
    $newEmail = $_POST["newEmail"];
    $email = $_POST["editAdminId"];
    if ($newEmail != $email) {
        $test_new_regNo = $query = "SELECT * FROM admin WHERE email = '" . $newEmail . "'";
        $check_res = mysqli_query($con, $test_new_regNo);
        if ($check_res->num_rows != 0) {
            $msg['error'] = "Email is already exists!";
        }
    }
    if (count($msg) == 0) {
        $name = $_POST["name"];
        $fullName = $_POST["fullName"];
        $role = $_POST["role"];
        $status = $_POST["status"];
        $mobileNo = ($_POST["mobileNo"] == "") ? 'NULL' : $_POST["mobileNo"];
        $department = $_POST["department"];

        $query = "UPDATE admin_details INNER JOIN admin ON admin_details.email = admin.email  
    SET admin.email = '$newEmail', admin.name = '$name', admin.role = '$role', admin.status = '$status', admin_details.fullName = '$fullName', admin_details.mobileNo = $mobileNo , admin_details.department = '$department' 
    WHERE admin.email = '" . $email . "'";
        $result = mysqli_query($con, $query);
        if ($result) {
            echo '<script>view("' . $newEmail . '");</script>';
            exit;
        } else {
            echo "Connection Failed : " . mysqli_connect_error();
        }
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

<?php if (isset($msg['error'])) : ?>
    <div class="msg error-msg"><?php echo $msg['error']; ?></div>
<?php endif; ?>


<div class="w-full mx-auto flex flex-col items-center gap-4">
    <h1 class="title">Admin Profile Edit</h1>

    <form method="post" action="" class="w-[500px] mx-auto flex flex-col items-center gap-4">
        <input type="hidden" name="editAdminId" value="<?php echo $row['adminId']; ?>"/>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="newEmail">Email:</label>
            <input type="text" name="newEmail" value="<?php echo $row['email']; ?>" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
            <input type="hidden" name="editAdminId" value="<?php echo $row['email']; ?>"/>

        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="role">Role:</label>
            <select for="role" name="role" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500">
                <option value="Admin_Student" <?php echo ("Admin_Student" == $row['role']) ? "selected" : ""; ?>>
                    Admin Student
                </option>
                <option value="Admin_Subject" <?php echo ("Admin_Subject" == $row['role']) ? "selected" : ""; ?>>
                    Admin Subject
                </option>
            </select>

        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="status">Status:</label>
            <select for="status" name="status" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500">
                <option value="active" <?php echo ("active" == $row['status']) ? "selected" : ""; ?>>
                    active
                </option>
                <option value="inactive" <?php echo ("inactive" == $row['status']) ? "selected" : ""; ?>>
                    inactive
                </option>
            </select>

        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $row['name']; ?>" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="fullName">Full Name:</label>
            <input type="text" name="fullName" value="<?php echo $row['fullName']; ?>" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="department">Department:</label>
            <input type="text" name="department" value="<?php echo $row['department']; ?>" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="mobileNo">Mobile No:</label>
            <input type="text" name="mobileNo" value="<?php echo $row['mobileNo']; ?>" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500"/>

        </div>

        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
            <button type="reset" name="reset" value="Reset" class="btn outline-btn">Reset</button>
            <button onclick="view('<?php echo $row['email']; ?>');return false;" class="btn outline-btn">Discard</button>
            <button type="submit" name="save" value="Save" class="btn fill-btn">Save</button>
        </div>
    </form>
</div>



<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>