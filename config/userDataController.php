<?php
ob_start();
if (!isset($_SESSION)) {
    session_start();
}

require($_SERVER['DOCUMENT_ROOT'] . '/Group 13/ERS-Web_Technologies/vendor/autoload.php'); // Include PHPMailer autoloader
require($_SERVER['DOCUMENT_ROOT'] . '/Group 13/ERS-Web_Technologies/config/connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = array();
$errors = array();


//if user Sign-Up button
if (isset($_POST['reg-btn'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    $regNoPattern1 = '/^\d{4}[A-Za-z]{2,3}\/?\d{3}$/';
    $regNoPattern2 = '/^\d{4}\/[A-Za-z]+\/\d{3}$/';
    if (preg_match($regNoPattern1, $username)) {
        $year = substr($username, 0, 4);
        // Determine the position of the department code and the number
        if (ctype_alpha($username[4]) && ctype_alpha($username[5]) && ctype_alpha($username[6])) {
            $department = substr($username, 4, 3);
            $number = substr($username, 7);
        } else {
            $department = substr($username, 4, 2);
            $number = substr($username, 6);
        }

        $username = $year ."/".$department ."/".$number;
    }

    // Check the name validation
    if (!preg_match($regNoPattern2, $username)) {
        $errors['username'] = "Invalid Registration No (XXXX/XXX/XXX)";
    } // Check the E-mail validation
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } // Check the Password validation
    elseif (strlen($_POST["password"]) <= '8') {
        $errors['password'] = "Your Password Must Contain At Least 8 Characters!";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
    } elseif (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $password)) {
        $errors['password'] = "Your Password Must Contain At Least 1 Special Character !";
    } // Check password and confirm password are the same
    elseif ($password !== $cpassword) {
        $errors['cpassword'] = "Confirm password not matched!";
    }

    if (count($errors) === 0) {
        // Find the student is a registered student in our university or not...
        $email_check = "SELECT * FROM student_check WHERE regNo = '$username' && email = '$email'";
        $email_check_res = mysqli_query($con, $email_check);
        if (mysqli_num_rows($email_check_res) === 0) {
            $errors['username'] = "Sorry, Your registration number does not exist! Please contact the admin panel.";
        } else {
            $fetch_email_check_res = mysqli_fetch_assoc($email_check_res);
            $fetch_user_status = $fetch_email_check_res['status'];

            // Find the email is already exist or not...
            if ($fetch_user_status === 'active') {
                $errors['username'] = "Your account is already registered!";
            }
        }

    }

    // Enter the user data into the database
    if (count($errors) === 0) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        $encpass = password_hash($password, PASSWORD_DEFAULT);
        $code = rand(999999, 111111);
        $status = "not_verified";

        $insert_data = "UPDATE  student_check set password='$encpass',  verificationCode='$code', verificationStatus='$status' WHERE regNo = '$username' and email = '$email'";
        $data_check = mysqli_query($con, $insert_data);

        // Mail the OTP code
        if ($data_check) {
            $subject = "ERS - Email Verification Code";
            $message = "Your verification code for the exam registration system is $code. This code will expire in 3 minutes";
            $sender_name = "Exam Registration System | Faculty of Science";
            $sender_mail = "ers.fos.csc@gmail.com";
            $htmlBody = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>OTP Email</title>
                </head>
                <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; padding: 20px;">
                    <div style="background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto;">
                        <h2 style="color: #333;">Exam Registration System</h2>
                        <p>Your verification code for the exam registration system is:</p>
                        <h1 style="color: #007bff;">'.$code.'</h1>
                        <p>This code will expire in 3 minutes.</p>
                    </div>
                </body>
                </html>
                ';

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ers.fos.csc@gmail.com';
                $mail->Password = 'izvixydstkhxvpsf';
                $mail->SMTPSecure = 'tls'; // Use TLS
                $mail->Port = 587;

                // Recipients and content
                $mail->setFrom($sender_mail, $sender_name);
                $mail->addAddress($email, $username);
                $mail->Subject = $subject;
                //$mail->Body = $message;
                $mail->msgHTML($htmlBody);

                // Send email
                $mail->send();
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['reg-code-sent'] = true;
                header('location: reg_verification.php');
            } catch (Exception $e) {
                $errors['error'] = "Failed while sending code!";
            }
        } else {
            $errors['error'] = "Failed while inserting data into database!";
        }
    }

}

//if user click verification code submit button
if (isset($_POST['verify-otp'])) {
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];

    $number1 = mysqli_real_escape_string($con, $_POST['number1']);
    $number2 = mysqli_real_escape_string($con, $_POST['number2']);
    $number3 = mysqli_real_escape_string($con, $_POST['number3']);
    $number4 = mysqli_real_escape_string($con, $_POST['number4']);
    $number5 = mysqli_real_escape_string($con, $_POST['number5']);
    $number6 = mysqli_real_escape_string($con, $_POST['number6']);

    $enteredOTP = $number1 * 100000 + $number2 * 10000 + $number3 * 1000 + $number4 * 100 + $number5 * 10 + $number6;

    $pull_code_query = "SELECT * FROM student_check WHERE regNo = '$username' and email = '$email'";
    $pull_code_res = mysqli_query($con, $pull_code_query);
    $fetch_verification_code = mysqli_fetch_assoc($pull_code_res);
    $verification_code = $fetch_verification_code['verificationCode'];

    if ($enteredOTP == $verification_code) {
        // Updating the user table status 
        $code = 0;
        $verificationStatus = 'verified';
        $status = 'active';
        $update_status = "UPDATE student_check SET verificationCode = $code, status = '$status', verificationStatus = '$verificationStatus' WHERE regNo = '$username' and email = '$email'";
        $update_res = mysqli_query($con, $update_status);
        if ($update_res) {
            header('location: ../login.php');
            exit();
        } else {
            $errors['otp-error'] = "Something went wrong!";
        }
    } else {
        $errors['wrong-otp'] = "You've entered incorrect code!";
    }
}

//if user click login button
if (isset($_POST['login-btn'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $table = "student_check";
    $field = "regNo";
    $role = "student";
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $table = "admin";
        $field = "email";
        $role = "admin";

    } else {
        // Check the name validation
        $regNoPattern1 = '/^\d{4}[A-Za-z]{2,3}\/?\d{3}$/';
        $regNoPattern2 = '/^\d{4}\/[A-Za-z]+\/\d{3}$/';
        if (preg_match($regNoPattern1, $username)) {
            $year = substr($username, 0, 4);
            // Determine the position of the department code and the number
            if (ctype_alpha($username[4]) && ctype_alpha($username[5]) && ctype_alpha($username[6])) {
                $department = substr($username, 4, 3);
                $number = substr($username, 7);
            } else {
                $department = substr($username, 4, 2);
                $number = substr($username, 6);
            }

            $username = $year ."/".$department ."/".$number;
        }

        if (!preg_match($regNoPattern2, $username)) {
            $errors['username'] = "Invalid Registration No (XXXX/XXX/XXX)";
        }

    }
    if (count($errors) === 0) {


        $check_email = "SELECT * FROM $table WHERE $field = '$username'";
        $res = mysqli_query($con, $check_email);

        if (mysqli_num_rows($res) > 0) {
            $fetch = mysqli_fetch_assoc($res);
            if ($fetch['status'] != "unregistered") {
                $fetch_pass = $fetch['password'];
                if (password_verify($password, $fetch_pass)) {

                    $status = $fetch['status'];
                    $email = $fetch['email'];
                    if ($status != "active") {
                        $errors['login-error'] = "Account is disabled!";
                    } else {
                        if ($role == "student") {
                            $verificationStatus = $fetch['verificationStatus'];
                            if ($verificationStatus != 'verified') {
                                $info = "It's look like you haven't still verify your email";
                                $_SESSION['info'] = $info;
                                header('location: ../register/reg_verification.php');
                            } else {
                                $_SESSION = array();
                                $_SESSION['userid'] = strtoupper($username);
                                header('location: student/');
                                exit();
                            }
                        } else {
                            $_SESSION['userid'] = $username;
                            $_SESSION['role'] = $fetch['role'];
                            $_SESSION['us_name'] = $fetch['name'];
                            header("location:admin_select.php");
                            exit();
                        }
                    }
                } else {
                    $errors['login-error'] = "Incorrect email or password!";
                }
            } else {
                $errors['login-error'] = "It's look like you didn't register yet! Click the bottom link to signup.";
            }
        } else {
            $errors['login-error'] = "Your details have not been updated yet! Please contact the admin.";
        }
    }
}

//if user click continue button in forgot password form
if (isset($_POST['forgot-pw-submit-btn'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $table = "student_check";
    $field = "regNo";
    $role = "student";
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $table = "admin";
        $field = "email";
        $role = "admin";

    }else{
        // Check the name validation
        $regNoPattern1 = '/^\d{4}[A-Za-z]{2,3}\/?\d{3}$/';
        $regNoPattern2 = '/^\d{4}\/[A-Za-z]+\/\d{3}$/';
        if (preg_match($regNoPattern1, $username)) {
            $year = substr($username, 0, 4);
            // Determine the position of the department code and the number
            if (ctype_alpha($username[4]) && ctype_alpha($username[5]) && ctype_alpha($username[6])) {
                $department = substr($username, 4, 3);
                $number = substr($username, 7);
            } else {
                $department = substr($username, 4, 2);
                $number = substr($username, 6);
            }

            $username = $year ."/".$department ."/".$number;
        }

        if (!preg_match($regNoPattern2, $username)) {
            $errors['error'] = "Invalid Registration No (XXXX/XXX/XXX)";
        }
    }
    if (count($errors) === 0) {
        $check_email = "SELECT * FROM $table WHERE $field = '$username' AND email = '$email'";
        $run_sql = mysqli_query($con, $check_email);

        if (mysqli_num_rows($run_sql) > 0) {
            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            $code = rand(999999, 111111);
            $insert_code = "UPDATE $table SET verificationCode = $code WHERE $field = '$username' AND email = '$email'";
            $run_query = mysqli_query($con, $insert_code);

            if ($run_query) {
                $subject = "ERS Registration - Email Verification Code";
                //$message = "Your verification code for the exam registration system is $code. This code will expire in 3 minutes";
                $sender_name = "Exam Registration System | Faculty of Science";
                $sender_mail = "ers.fos.csc@gmail.com";
                $htmlBody = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>OTP Email</title>
                </head>
                <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; padding: 20px;">
                    <div style="background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto;">
                        <h2 style="color: #333;">Exam Registration System</h2>
                        <p>Your verification code for the exam registration system is:</p>
                        <h1 style="color: #007bff;">'.$code.'</h1>
                        <p>This code will expire in 3 minutes.</p>
                    </div>
                </body>
                </html>
                ';

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ers.fos.csc@gmail.com';
                    $mail->Password = 'izvixydstkhxvpsf';
                    $mail->SMTPSecure = 'tls'; // Use TLS
                    $mail->Port = 587;

                    // Recipients and content
                    $mail->setFrom($sender_mail, $sender_name);
                    $mail->addAddress($email, $username);
                    $mail->Subject = $subject;
                    //$mail->Body = $message;
                    $mail->msgHTML($htmlBody);

                    // Send email
                    $mail->send();
                    $info = "We've sent a verification code to your email - $email";
                    $_SESSION['fp-email'] = $email;
                    $_SESSION['fp-username'] = $username;
                    $_SESSION['code-sent'] = true;
                    header('location: user_verification.php');
                } catch (Exception $e) {
                    $errors['error'] = "Failed while sending code!";
                }
            } else {
                $errors['error'] = "Something went wrong!";
            }
        } else {
            $errors['error'] = "This username or email does not exist!";
        }
    }
}

//if user click verification code submit button
if (isset($_POST['verify-pw-otp'])) {
    $email = $_SESSION['fp-email'];
    $username = $_SESSION['fp-username'];

    $number1 = mysqli_real_escape_string($con, $_POST['number1']);
    $number2 = mysqli_real_escape_string($con, $_POST['number2']);
    $number3 = mysqli_real_escape_string($con, $_POST['number3']);
    $number4 = mysqli_real_escape_string($con, $_POST['number4']);
    $number5 = mysqli_real_escape_string($con, $_POST['number5']);
    $number6 = mysqli_real_escape_string($con, $_POST['number6']);

    $enteredOTP = $number1 * 100000 + $number2 * 10000 + $number3 * 1000 + $number4 * 100 + $number5 * 10 + $number6;
    $table = "student_check";
    $field = "regNo";
    $role = "student";
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $table = "admin";
        $field = "email";
        $role = "admin";

    }
    $pull_code_query = "SELECT * FROM $table WHERE $field = '$username' AND email = '$email'";
    $pull_code_res = mysqli_query($con, $pull_code_query);
    $fetch_verification_code = mysqli_fetch_assoc($pull_code_res);
    $verification_code = $fetch_verification_code['verificationCode'];

    if ($enteredOTP == $verification_code) {
        // Updating the user table status
        $code = 0;
        $verificationStatus = 'verified';
        $update_otp = "UPDATE $table SET verificationCode = $code, verificationStatus = '$verificationStatus' WHERE $field = '$username' and email = '$email'";
        $update_res = mysqli_query($con, $update_otp);
        unset($_SESSION['code-sent']);
        if ($update_res) {
            $_SESSION['fp-email'] = $email;
            $_SESSION['fp-username'] = $username;
            $_SESSION['code-verified'] = true;
            header('location: reset_password.php');
            exit();
        } else {
            $errors['otp-error'] = "Something went wrong!";
        }
    } else {
        $errors['wrong-otp'] = "You've entered incorrect code!";
    }
}


if (isset($_GET['reg-code-resend'])) {
    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    $email = $_SESSION['fp-email'];
    $username = $_SESSION['fp-username'];
    $code = rand(999999, 111111);
    $insert_code = "UPDATE student_check SET verificationCode = $code WHERE regNo = '$username' and email = '$email'";
    $run_query = mysqli_query($con, $insert_code);
    if ($run_query) {
        $subject = "ERS Registration - Email Verification Code";
        //$message = "Your verification code for the exam registration system is $code. This code will expire in 3 minutes";
        $sender_name = "Exam Registration System | Faculty of Science";
        $sender_mail = "ers.fos.csc@gmail.com";
        $htmlBody = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>OTP Email</title>
                </head>
                <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; padding: 20px;">
                    <div style="background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto;">
                        <h2 style="color: #333;">Exam Registration System</h2>
                        <p>Your verification code for the exam registration system is:</p>
                        <h1 style="color: #007bff;">'.$code.'</h1>
                        <p>This code will expire in 3 minutes.</p>
                    </div>
                </body>
                </html>
                ';
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ers.fos.csc@gmail.com';
            $mail->Password = 'izvixydstkhxvpsf';
            $mail->SMTPSecure = 'tls'; // Use TLS
            $mail->Port = 587;

            // Recipients and content
            $mail->setFrom($sender_mail, $sender_name);
            $mail->addAddress($email, $username);
            $mail->Subject = $subject;
            //$mail->Body = $message;
            $mail->msgHTML($htmlBody);

            // Send email
            $mail->send();
            $info = "We've sent a verification code to your email - $email";
            $_SESSION['fp-email'] = $email;
            $_SESSION['fp-username'] = $username;
            $_SESSION['code-sent'] = true;
            header('location: ../register/reg_verification.php');
        } catch (Exception $e) {
            $errors['error'] = "Failed while sending code!";
        }
    } else {
        $errors['error'] = "Something went wrong!";
    }
}


if (isset($_GET['pw-code-resend'])) {
    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    $email = $_SESSION['fp-email'];
    $username = $_SESSION['fp-username'];
    $table = "student_check";
    $field = "regNo";
    $role = "student";
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $table = "admin";
        $field = "email";
        $role = "admin";

    }
    $code = rand(999999, 111111);
    $insert_code = "UPDATE $table SET verificationCode = $code WHERE $field = '$username' AND email = '$email'";
    $run_query = mysqli_query($con, $insert_code);
    if ($run_query) {
        $subject = "ERS Registration - Email Verification Code";
        //$message = "Your verification code for the exam registration system is $code. This code will expire in 3 minutes";
        $sender_name = "Exam Registration System | Faculty of Science";
        $sender_mail = "ers.fos.csc@gmail.com";
        $htmlBody = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>OTP Email</title>
                </head>
                <body style="font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; padding: 20px;">
                    <div style="background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto;">
                        <h2 style="color: #333;">Exam Registration System</h2>
                        <p>Your verification code for the exam registration system is:</p>
                        <h1 style="color: #007bff;">'.$code.'</h1>
                        <p>This code will expire in 3 minutes.</p>
                    </div>
                </body>
                </html>
                ';
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ers.fos.csc@gmail.com';
            $mail->Password = 'izvixydstkhxvpsf';
            $mail->SMTPSecure = 'tls'; // Use TLS
            $mail->Port = 587;

            // Recipients and content
            $mail->setFrom($sender_mail, $sender_name);
            $mail->addAddress($email, $username);
            $mail->Subject = $subject;
            //$mail->Body = $message;
            $mail->msgHTML($htmlBody);

            // Send email
            $mail->send();
            $info = "We've sent a verification code to your email - $email";
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['code-sent'] = true;
            header('Location:../login/user_verification.php');
        } catch (Exception $e) {
            $errors['error'] = "Failed while sending code!";
        }
    } else {
        $errors['error'] = "Something went wrong!";
    }
}


//if user click change password button
if (isset($_POST['reset-pw-btn'])) {
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

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

    // Check the password and confirm password are same
    if ($password !== $cpassword) {
        $errors['cpassword'] = "Confirm password not matched!";
    } else {
        $code = 0;
        $email = $_SESSION['fp-email']; //getting this email using session
        $username = $_SESSION['fp-username']; //getting this username using session
        $table = "student_check";
        $field = "regNo";
        $role = "student";
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $table = "admin";
            $field = "email";
            $role = "admin";

        }
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE $table SET verificationCode = $code, password = '$encpass' WHERE $field = '$username' AND email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if ($run_query) {
            unset($_SESSION['fp-email']);
            unset($_SESSION['fp-username']);
            unset($_SESSION['code-verified']);
            header('Location: ../login.php');
        } else {
            $errors['error'] = "Failed to change your password!";
        }
    }
}

