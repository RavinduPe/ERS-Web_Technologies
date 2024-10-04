<?php
ob_start();
session_start();
if (isset($_SESSION['userid'])) {
    if (isset($_SESSION['role']))
        header("location:../admin_select.php");
    else
        header("location:../");
}
require_once('../config/userDataController.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="../assets/img/logo/ERS_logo_icon.ico"
      type="image/x-icon" />
    <title>ERS | Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script
      src="https://kit.fontawesome.com/5ce4b972fd.js"
      crossorigin="anonymous"></script>
  </head>

  <body class="h-screen w-full lg:relative">
    <div class="login-bg flex items-center justify-center"></div>
    <div class="card h-[495px] w-10/12 lg:w-7/12 absolute-center lg:h-[450px] lg:p-0 z-0">
        <!-- Mobile n tab view design -->
        <div class="lg:h-full flex lg:grid flex-col z-10 lg:grid-cols-2 items-center lg:justify-items-center">
            <img
              src="../assets/img/logo/ERS_logo.gif"
              alt="ERS_logo"
              class="w-28 align-middle lg:col-start-1 lg:self-end " />
            <h2 class="my-5 text-lg font-[var(--title)] lg:my-0 lg:col-start-1 ">Exam Registration System</h2>
            <img
                src="../assets/img/undraw_forgot_password.svg"
                alt="vector img"
                class="hidden w-60 lg:block lg:col-start-1 " />
            <h3 class="text-lg underline font-semibold text-gray-900 mb-3 lg:text-2xl lg:mb-0 lg:col-start-2 lg:row-start-1 lg:self-end">
                Sign-In
            </h3>
            <?php if (isset($errors['error'])) { ?>
                <div class="error-text lg:col-start-2 lg:row-start-2 lg:self-end"><?php echo $errors['error']; ?></div>
            <?php } ?>

            <form
              action="forgot_password.php"
              method="post"
              class="flex flex-col items-center justify-around lg:col-start-2 lg:row-span-2 lg:self-start lg:w-full">

              <div class="text-input lg:w-9/12">
                  <i class="fa-solid fa-user"></i>
                  <div></div>
                  <input type="text" name="username" placeholder="UserName" class="lg:w-full" />
              </div>

              <div class="text-input lg:w-9/12">
                  <i class="fa-solid fa-lock"></i>
                  <div></div>
                  <input type="email" name="email" placeholder="E-mail" class="lg:w-full" />
              </div>

              <input
                type="submit"
                name="forgot-pw-submit-btn"
                value="Submit"
                class="btn lg:mt-5 text-white bg-[var(--primary)]" />
            </form>
            <div class="text-center mt-7 lg:col-start-1 lg:mt-0 ">
                <p>Remember Password?</p>
                <a href="../login.php" class="text-[var(--primary)] underline"
                >Go Back</a>
            </div>
        </div>
        <div class="-z-10 lg:absolute lg:inset-2/4 lg:-translate-x-full lg:-translate-y-1/2 lg:w-1/2 lg:h-full lg:bg-[#bfd7ff] lg:rounded-2xl"></div>
    </div>
  </body>
</html>
