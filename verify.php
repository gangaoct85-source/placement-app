<?php
include 'config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$msg = "";
$email = "";

/* Get email from URL or POST */
if(isset($_GET['email'])) $email = $_GET['email'];
if(isset($_POST['email'])) $email = $_POST['email'];

/* SEND OTP */
if(isset($_POST['sendotp']) && $email!=""){
    $otp = rand(100000,999999);
    mysqli_query($conn,"UPDATE users SET otp='$otp' WHERE email='$email'");

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "gangaoct85@gmail.com";
    $mail->Password = "mldbomdijowxzuwn";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("gangaoct85@gmail.com","GVN College");
    $mail->addAddress($email);
    $mail->Subject = "GVN College OTP";
    $mail->Body = "Your OTP is $otp";

    $mail->send();
    $msg = "OTP sent to your email";
}

/* VERIFY OTP */
if(isset($_POST['verify']) && $email!=""){
    $otp = $_POST['otp'];
    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND otp='$otp'");
    if(mysqli_num_rows($q)==1){
        mysqli_query($conn,"UPDATE users SET otp_status='verified' WHERE email='$email'");
        echo "<h3>Email Verified</h3><a href='login.php'>Login</a>";
        exit();
    } else {
        $msg = "Invalid OTP";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Email Verification</h2>
<p style="color:green"><?php echo $msg; ?></p>

<form method="post">
<input type="email" name="email" value="<?php echo $email; ?>" placeholder="Enter Email" required><br><br>

<button name="sendotp">Send OTP</button><br><br>

<input type="text" name="otp" placeholder="Enter OTP"><br><br>
<button name="verify">Verify</button>
</form>

</body>
</html>

