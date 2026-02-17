<?php
// Basic Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

// Secret Key Validation
$secret_key = "6afscdh8dgdugdg6fit";

if (!isset($_POST['submit'])) {
    exit("Invalid Request");
}

// CSRF Secret Key Check
if (!isset($_POST['secret_key']) || $_POST['secret_key'] !== $secret_key) {
    exit("Unauthorized access");
}

// Sanitize Function (Prevent XSS)
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$full_name = clean_input($_POST['full_name'] ?? '');
$company_name = clean_input($_POST['company_name'] ?? '');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = clean_input($_POST['phone'] ?? '');
$website = clean_input($_POST['website'] ?? '');
$service_required = clean_input($_POST['service_required'] ?? '');
$project_budget = clean_input($_POST['project_budget'] ?? '');
$message = clean_input($_POST['message'] ?? '');

// Validate Required Fields
if (empty($full_name) || empty($email) || empty($message)) {
    exit("Please fill required fields.");
}

// Email Validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email format.");
}

// Load PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    // SMTP Settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'wisemenmarketingofficial@gmail.com'; // Replace
    $mail->Password = 'lhqt ikbf hjkx rchi'; // Replace
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('wisemenmarketingofficial@gmail.com');
    $mail->addAddress('wisemenmarketingofficial@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = "New Enquiry - $service_required";

    // Styled Email Template
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; background:#f5f5f5; padding:30px;'>
        <div style='max-width:600px; margin:auto; background:#ffffff; border-radius:8px; overflow:hidden;'>
            
            <div style='background:#000000; padding:20px; text-align:center;'>
                <h2 style='color:#c49c4d; margin:0;'>New Strategy Call Request</h2>
            </div>
            
            <div style='padding:25px; color:#333;'>
                <p><strong>Full Name:</strong> $full_name</p>
                <p><strong>Company Name:</strong> $company_name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Website:</strong> $website</p>
                <p><strong>Service Required:</strong> 
                    <span style='color:#c49c4d; font-weight:bold;'>$service_required</span>
                </p>
                <p><strong>Project Budget:</strong> ₹$project_budget</p>
                
                <hr style='border:1px solid #c49c4d;'>
                
                <p><strong>Message:</strong></p>
                <p style='background:#f9f9f9; padding:15px; border-left:4px solid #c49c4d;'>
                    $message
                </p>
            </div>
            
            // <div style='background:#000000; padding:15px; text-align:center;'>
            //     <small style='color:#c49c4d;'>Your Company Name | Confidential Inquiry</small>
            // </div>
        </div>
    </div>
    ";

    $mail->send();
    echo "Message sent successfully";

} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>
