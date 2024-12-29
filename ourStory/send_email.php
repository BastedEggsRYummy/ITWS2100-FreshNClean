<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form fields
    $fname = htmlspecialchars(strip_tags(trim($_POST["fname"])));
    $lname = htmlspecialchars(strip_tags(trim($_POST["lname"])));
    $email = htmlspecialchars(strip_tags(trim($_POST["email"])));
    $subject = htmlspecialchars(strip_tags(trim($_POST["subject"])));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'freshncleanfeedback@gmail.com';
        $mail->Password = getenv('GOOGLEPWD'); // google app specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('freshncleanfeedback@gmail.com', 'Feedback Form');
        $mail->addAddress('freshncleanfeedback@gmail.com', 'The Team');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<strong>Name:</strong> $fname $lname<br><strong>Email:</strong> $email<br><br>$message";

        $mail->send();
        echo 'Message has been sent. Our team will contact you within 3-5 business days.';
    } catch (Exception $e) {
        http_response_code(500);
        echo "Message could not be sent. Please try again.";
    }
} 
else {
    http_response_code(405);
    echo "Invalid request.";
}

?>
