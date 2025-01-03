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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/loginStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="heading-container">
        <h2 class="typing-animation">Reset Password</h2>
    </div>
    <div class="form-container">
        <form id="resetForm" role="form" method="post" action="process-reset-password.php?token=<?php echo $_GET['token']; ?>">
            <div class="input-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter New Password" required>
            </div>
            <div class="input-group">
                <label for="repeatPassword">Repeat Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat New Password" required>
            </div>
            <div id="submitButton">
                <button type="submit">Send</button>
            </div>
        </form>
    </div>
</body>
</html>
