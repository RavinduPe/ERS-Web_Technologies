<?php
ob_start();
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:../index.php");
    exit();
}
elseif (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "Admin_Master") {
        header("Location:../master");
        exit;
    } else {
        header("Location:../admin");
        exit;
    }
}

include("../config/connect.php");
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'); // Include PHPMailer autoloader


$regNo = $_SESSION['userid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = array();
$errors = array();

if ($_POST['submit']) {
    $mail = new PHPMailer(true);

    $name = $_POST['name'];
    $email = $_POST['email'];
    $userSubject = $_POST['subject'];
    $userMessage = $_POST['message'];

    // Mail the OTP code
    $subject = "ERS - $name($regNo) | $userSubject";
    $message = $userMessage;
    $sender_name = $name;
    $sender_mail = $email;
    $htmlBody = "
        <div style='font-family: Arial, sans-serif; background-color: #f0f0f0; padding: 20px;'>
            <div style='background-color: #ffffff; border-radius: 10px; padding: 20px; max-width: 90%; margin: 0 auto;'>
                <p>$userMessage</p>
                <div style='text-align: right;'>
                    <p style='font-weight: 600;'>$name</p>
                    <p style='color: rgb(82 82 91); font-size: 10px'>$regNo</p>
                </div>
            </div>
        </div>
        ";

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
        $mail->addAddress($email, $username); // TODO:This has to change to dean office email and name. for now it is sending to the same user who sent the message. 
        $mail->Subject = $subject;
        //$mail->Body = $message;
        $mail->msgHTML($htmlBody);

        // Send email
        $sendResult = $mail->send();

        if ($sendResult) {
            header("Location: contact.php?success=Message sent Successfully. We will contact you soon.");
        }
    } catch (Exception $e) {
        header("Location: contact.php?error=Message sent Unsuccessfull! Something went wrong.");
    }    
}

$errors = array();
$selectSQL = "SELECT * FROM student WHERE regNo = '$regNo';";
$selectQuery = mysqli_query($con, $selectSQL);
$user = mysqli_fetch_assoc($selectQuery);

$nameWithInitial = isset($user["nameWithInitial"]) ? $user["nameWithInitial"] : "";
$email = isset($user["email"]) ? $user["email"] : "";
$profile_img = isset($user['profile_img']) ? $user['profile_img'] : "blankProfile.png";
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
        rel="shortcut icon"
        href="../assets/img/logo/ERS_logo_icon.ico"
        type="image/x-icon" />
    <title>ERS | Contact</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
    <script
    src="https://kit.fontawesome.com/5ce4b972fd.js"
    crossorigin="anonymous"></script>
</head>
<body class=" bg-gray-50 sm:text-xs xl:text-sm 2xl:text-base" id="student">
    <nav class="w-full h-[15vh] min-h-fit drop-shadow-md bg-white fixed top-0 left-0">
        <div class="w-10/12 h-full m-auto flex items-center justify-between">
            <a href="index.php">
                <img src="../assets/img/logo/ERS_logo.gif" alt="logo" class="w-28 align-middle">
            </a>    
            <ul class="flex items-center justify-around gap-10">
                <li><a href="exam_reg.php" class="hidden btn outline-btn md:block">Exam Registration</a></li>

                <?php if (!isset($profile_img)) { ?>
                    <li onclick="openMenu()" class="py-2 px-[14px] bg-[var(--primary)] rounded-full drop-shadow-md cursor-pointer lh:relative">
                        <i class="fa-solid fa-user text-2xl text-[#dfeaff]"></i>
                    </li>
                <?php } else { ?>
                    <li onclick="openMenu()" class="w-10 h-10 lg:w-12 lg:h-12 rounded-full drop-shadow-md cursor-pointer ring-4">
                        <img src="../assets/uploads/<?php echo $profile_img; ?>" alt="user img" class="w-full h-full rounded-full">
                    </div>
                <?php } ?>
                
            </ul>
                       
        </div>
        <div class="hidden top-[14.8vh] right-0 h-56 w-full bg-white -translate-y-full z-20 transition-transform lg:top-[16vh] lg:drop-shadow-2xl lg:right-24 lg:w-56 lg:translate-x-full lg:h-72 lg:rounded-tl-3xl lg:rounded-br-3xl lg:text-gray-800" id="user-menu">
            <ul class="w-11/12 py-3 h-full m-auto flex flex-col items-center justify-around text-center">
                <li><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="exam_reg.php">Exam Registration</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="index.php">Dashboard</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li class=""><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="contact.php">Contact</a></li>
                <div class="h-px w-3/4 bg-gray-300"></div>
                <li><a class="py-4 hover:text-blue-600 hover:font-bold hover:tracking-wide transition-all" href="../logout.php">Logout</a></li>
            </ul>   
        </div>   
    </nav>

    <!-- Displaying Notification -->
    <?php if (isset($_GET['error'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7" action="contact.php" method="POST">
                <p class="text-center text-red-500"><?php echo $_GET['error'] ?></p>
                <input class="btn fill-btn !bg-red-500" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } elseif (isset($_GET['success'])) { ?>
        <div class="exam-false fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex justify-center items-center">
            <form class="card h-40 w-10/12 lg:w-1/2 flex flex-col items-center justify-around gap-7" action="contact.php" method="POST">
                <p class="text-center text-green-700"><?php echo $_GET['success'] ?></p>
                <input class="btn fill-btn !bg-green-700" type="submit" value="OK" name="ok">
            </form>
        </div>
    <?php } ?>

    <div class="body-sec my-[20vh]">
        <div class="container m-auto">
            <div class="card w-11/12 lg:w-2/3 m-auto">
                <h1 class="font-bold text-lg tracking-wide text-center">Contact The Dean Office</h1>
                <form method="POST" action="contact.php" class="mt-5 w-10/12 mx-auto flex flex-col gap-y-3">
                    <div class="detail-row my-1 !block lg:!grid !w-full">
                        <label class="hidden lg:block" for="name">Your Name: <span class="text-red-500">*</span></label>
                        <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="name" name="name" value="<?php echo $nameWithInitial; ?>" placeholder="Your Name" required >
                    </div>
                    <div class="detail-row my-1 !block lg:!grid !w-full">
                        <label class="hidden lg:block" for="email">Your Email: <span class="text-red-500">*</span></label>
                        <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="Your Email" required >
                    </div>
                    <div class="detail-row my-1 !block lg:!grid !w-full">
                        <label class="hidden lg:block" for="subject">Message Title (Subject): <span class="text-red-500">*</span></label>
                        <input class="inputs lg:placeholder:text-transparent  w-full lg:w-1/2" type="text" id="subject" name="subject" placeholder="Message title (Subject)" required >
                    </div>
                    <div class="detail-row my-1 !block lg:!grid !w-full">
                        <label class="hidden lg:block" for="message">Message: <span class="text-red-500">*</span></label>
                        <textarea class="inputs lg:placeholder:text-transparent " id="message" name="message" rows="8" placeholder="Message" required></textarea>
                    </div>
                    <input class="inputs w-full btn fill-btn !my-4 lg:my-0" type="submit"  name="submit" value="Send" class="btn fill-btn">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    const userMenu = document.getElementById('user-menu');

    function openMenu() {
        userMenu.classList.toggle('hidden');
        userMenu.classList.toggle('absolute');
        userMenu.classList.toggle('-translate-y-full');
        userMenu.classList.toggle('lg:translate-x-full');
    }
</script>