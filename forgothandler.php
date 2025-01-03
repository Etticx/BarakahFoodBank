<?php
$email = $_POST["clientEmailAddress"];
$token = bin2hex(random_bytes(16));
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require 'dbConnect.php';

$sql = "UPDATE client
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE clientEmailAddress = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token, $expiry, $email);
$stmt->execute();

if($stmt->affected_rows){
    $mail = require "mailer.php";
    $mail->setFrom("barakahcontact123@gmail.com", "Barakah Food Bank");
    $mail->addAddress($email);
    $mail->Subject = "PASSWORD RESET REQUEST";
    $mail->Body = <<<END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        .email-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-header {
            background-color: #0277bd;
            color: #fff;
            padding: 10px;
            border-radius: 10px 10px 0 0;
        }
        .email-body {
            margin: 20px 0;
        }
        .email-footer {
            color: #777;
            font-size: 12px;
        }
        a {
            color: #0277bd;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="email-body">
            <p>Dear Valued User,</p>
            <p>We received a request to reset your password for your account associated with this email address.</p>
            <p>To proceed with the password reset, please click on the link below:</p>
            <p><a href="http://localhost/barakahfoodbank/reset-password.php?token=$token">Reset Your Password</a></p>
            <p>If you did not request this password reset, please ignore this email. Your current password will remain unchanged.</p>
            <p>If you have any questions or need further assistance, please contact our support team.</p>
        </div>
        <div class="email-footer">
            <p>Best Regards,<br>Barakah Food Bank Team</p>
            <p>This email was sent to you by Barakah Food Bank. If you did not make this request, please contact us at barakahcontact123@gmail.com.</p>
        </div>
    </div>
</body>
</html>
END;

    try{
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }

    // Redirect to loginform.html after a short delay
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="3;url=loginform.html">
        <title>Redirecting...</title>
        <style>
            body {
                font-family: 'Raleway', sans-serif;
                text-align: center;
                margin-top: 20px;
                background-color: #b3e5fc; /* Baby blue background */
            }
            h1 {
                color: #0277bd; /* Darker shade of blue */
            }
            p {
                color: #0277bd; /* Darker shade of blue */
            }
        </style>
    </head>
    <body>
        <h1>Password reset instructions sent</h1>
        <p>You will be redirected to the login page shortly...</p>
    </body>
    </html>
    HTML;

    // End script execution
    exit;
}

echo "Message sent, please check your inbox.";
?>
