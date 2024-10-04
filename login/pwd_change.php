<?php
$msg = array();
require_once("../config/connect.php");
$pwdUser = $_SESSION['userid'];
if (isset($_POST['chg-pwd'])) {
// Get user input from the form
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

// Validate new password and confirm password
    if ($newPassword !== $confirmNewPassword) {
        $msg['error'] = "Error: New password and confirm new password do not match.";
    }
    if (strlen($newPassword) <= '8') {
        $msg['error'] = "Your Password Must Contain At Least 8 Characters!";
    } elseif (!preg_match("#[0-9]+#", $newPassword)) {
        $msg['error'] = "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $newPassword)) {
        $msg['error'] = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $newPassword)) {
        $msg['error'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
    } elseif (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $newPassword)) {
        $msg['error'] = "Your Password Must Contain At Least 1 Special Character !";
    }
    if(count($msg) == 0) {
// Check if the current password is correct for the user
        $sql = "SELECT password FROM admin WHERE email = '" . $pwdUser . "';";
        $result = $con->query($sql);


        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Simulated password validation (replace with your actual password validation logic)
            if (password_verify($currentPassword, $storedPassword)) {
                // Hash the new password before storing it
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $updateSql = "UPDATE admin SET password = '$hashedPassword' WHERE email = '$pwdUser'";

                if ($con->query($updateSql) === TRUE) {
                    $msg['info'] = "Password changed successfully!";
                } else {
                    $msg['error'] = "Error updating password: " . $con->error;
                }
            } else {
                $msg['error'] = "Incorrect current password.";
            }
        }
    }
} ?>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>



<div class="w-10/12 mx-auto ">
    <h2 class="title text-center mb-10">Change Password</h2>
    <?php if (isset($msg['error'])) : ?>
        <div class="error-text"><?php echo $msg['error']; ?></div>
    <?php endif; ?>

    <?php if (isset($msg['info'])) : ?>
        <div class="error-text !text-green-500"><?php echo $msg['info']; ?></div>
    <?php endif; ?>

    <form action="" method="post" class="flex flex-col items-center justify-around gap-6">
        <div class="detail-row">
            <label for="currentPassword">Current Password:</label>
            <input type="password" class="w-full border-2 border-gray-500 rounded-full outline-none py-2 px-4" id="currentPassword" name="currentPassword" required>

        </div>

        <div class="detail-row">
            <label for="newPassword">New Password:</label>
            <input type="password" class="w-full border-2 border-gray-500 rounded-full outline-none py-2 px-4" id="newPassword" name="newPassword" required>
        </div>

        <div class="detail-row">
            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" class="w-full border-2 border-gray-500 rounded-full outline-none py-2 px-4" id="confirmNewPassword" name="confirmNewPassword" required>
        </div>


        <div class="w-1/2 grid grid-cols-3 items-center h-10 gap-5 mt-5 mb-10">
            <input type="button" value="< Back" onclick="history.back()" class="btn outline-btn">
            <button type="submit" name="chg-pwd" class="col-span-2 w-full btn fill-btn">Change Password</button>
        </div>
    </form>
</div>

