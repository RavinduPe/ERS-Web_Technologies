<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin_Master") {
    header("location:../login.php");
    exit();
}


$errors =array();
if (isset($_POST['submit'])) {
    $email = strtolower($_POST['email']);
    $name = $_POST['name'];
    $password = $_POST["password"];
    // Check the Password validation
    if (strlen($_POST["password"]) <= '8') {
        $errors['password'] = "Your Password Must Contain At Least 8 Characters!";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
    } elseif (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Special Character !";
    }
    if (count($errors) === 0) {

        $query = "SELECT * from admin where email='$email'";

        if (mysqli_num_rows(mysqli_query($con, $query))) {

            $msg[0] = "Email already exists!";
            $msg[1] = "warning";
        } else {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];

            $query = "INSERT INTO admin (email,password,role,name) values('$email','$password','$role','$name')";
            if (!mysqli_query($con, $query)) {

                $msg[0] = "error!";
                $msg[1] = "warning";
            } else {
                $query = "INSERT INTO admin_details (email) values('$email')";
                mysqli_query($con, $query);
                $msg[0] = "Successfully added!";
                $msg[1] = "done";
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
    <h1 class="title mb-5">Add Admin</h1>
    <form action="" method="post" class="w-full flex flex-col items-center gap-5">
        <?php
        if (isset($msg)) {
            echo "<b class='" . $msg[1] . "'>" . $msg[0] . "</b>";
        }
        ?>
        <?php
        if (isset($errors['password'])) {
            echo "<b>" . $errors['password'] . "</b>";
        }
        ?>

        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="name">Name: </label>
            <input type="text" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="name" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="email">Email: </label>
            <input type="email" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="email" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="password">Password: </label>
            <input type="password" class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="password" required>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10">
            <label for="role">Role: </label>
            <select class="col-span-2 w-full h-full border-2 border-gray-400 rounded-full px-5 outline-none focus:border-blue-500" name="role">
                <option value="Admin_Subject">Admin_Subject</option>
                <option value="Admin_Student">Admin_Student</option>
            </select>
        </div>
        <div class="w-full grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <input type="submit" name="submit" value="Register" class="col-span-2 w-full btn fill-btn">
        </div>



    </form>
</div>