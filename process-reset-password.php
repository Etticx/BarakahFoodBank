<?php
$token = $_GET["token"];
$mysqli = require "dbConnect.php";

$sql = "SELECT * FROM client
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user == null) {
    die("token not found");
}

if(strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$sql = "UPDATE client
        SET clientPassword = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE clientID = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $_POST["password"], $user["clientID"]);
$stmt->execute();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=loginform.html">
    <title>Password Updated</title>
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
    <h1>Password Updated</h1>
    <p>You can now login with your new password.</p>
    <p>You will be redirected to the login page shortly...</p>
</body>
</html>
