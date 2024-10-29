<?php
require '../vendor/autoload.php'; // This will automatically load PHPMailer


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../config/connect.php");
if(isset($_POST['send_email'])){
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kavishkaimalsha@gmail.com';
    $mail->Password = 'laza cnkr umsw pnui';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $adminMailQuery = "select email from admin where role='Admin_Master';";
    $fromResult = mysqli_query($con,$adminMailQuery);
    $from = mysqli_fetch_assoc($fromResult);
    $fromEmail = $from['email'];

    $mail->setFrom($fromEmail, 'Master Admin');
    $mail->addReplyTo($fromEmail, 'Master Admin');

    $query = "select email from admin where role='Admin_Student';";
    $toMails = mysqli_query($con, $query);
    while($to = mysqli_fetch_assoc($toMails)){
        $toEmail = $to['email'];
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = "Inform to assign students to exam";
        $mail->Body = '<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <p>Add students for Exams</p><br/>
        <p>Regards</p><br/>
        '.$fromEmail.'
    </div>
</body>
</html>';
        if($mail->send()){
            echo '<script>alert("Message send successfully")</script>';
        }
        else{
            echo '<script>alert("!!! message send unsuccessfully !!!" . '.$mail->ErrorInfo.')</script>';
        }

    }
    echo '<script>window.location.href="index.php";</script>';
}
