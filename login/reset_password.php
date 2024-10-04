<?php
ob_start();
if (isset($_SESSION['userid'])) {
    if (isset($_SESSION['role']))
        header("location:../admin_select.php");
    else
        header("location:../");
}
require_once('../config/userDataController.php');

if (!isset($_SESSION['code-verified'])) {
  header("location: ../index.php");
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="../assets/img/logo/ERS_logo_icon.ico"
      type="image/x-icon" />
    <title>ERS | Reset Password</title>
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
          Reset Password
        </h3>
        <!-- error msgs -->
        <?php if (isset($errors['error'])) { ?>
            <div class="error-text lg:col-start-2 lg:row-start-2 lg:self-end"><?php echo $errors['error']; ?></div>
        <?php } ?>

        <form
          action="reset_password.php"
          method="post"
          class="flex flex-col items-center justify-around lg:col-start-2 lg:row-span-2 lg:self-start lg:w-full">

          <!-- error msg -->
          <?php if (isset($errors['password'])) { ?>
            <p class='error-text'><?php echo $errors['password']; ?></p>
          <?php } ?>
          <!-- password input -->
          <div class="<?php echo (isset($errors['password']) ? "error-input" : "")?> text-input lg:w-9/12">
            <i class="fa-solid fa-lock"></i>
            <div></div>
            <input type="password" class="lg:w-full" name="password" placeholder="Password" />
          </div>
          <!-- error msg -->
          <?php if (isset($errors['cpassword'])) { ?>
            <p class='error-text'><?php echo $errors['cpassword']; ?></p>
          <?php } ?>
          <!-- confirm pw input -->
          <div class="<?php echo (isset($errors['cpassword']) ? "error-input" : "")?> text-input lg:w-9/12">
            <i class="fa-solid fa-lock"></i>
            <div></div>
            <input
              type="password"
              class="lg:w-full"
              name="cpassword"
              placeholder="Confirm Password" />
          </div>
          
          <input
            type="submit"
            name="reset-pw-btn"
            value="Reset"
            class="btn text-white bg-[var(--primary)] mt-5" />
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
