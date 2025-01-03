<?php
session_start();

// Include the database connection file
require_once 'dbConnect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted username and password
    $username = $_POST['clientUsername'];
    $password = $_POST['clientPassword'];

    // Sanitize the input to prevent SQL injection
    $username = mysqli_real_escape_string($mysqli, $username);
    $password = mysqli_real_escape_string($mysqli, $password);

    // Query the database for the user
    $sql = "SELECT * FROM client WHERE clientUsername = '$username' AND clientPassword = '$password'";
    $result = mysqli_query($mysqli, $sql);

    // Check if the user exists
    if (mysqli_num_rows($result) > 0) {
        // User found, retrieve clientID and store in session
        $row = mysqli_fetch_assoc($result);
        $_SESSION['clientID'] = $row['clientID'];

        // Redirect to index.html
        header("Location: index.html");
        exit();
    } else {
        // User not found or incorrect password
        echo "<script>alert('Incorrect username or password.'); window.location.href='loginform.html';</script>";
    }
}

// Close the database connection
mysqli_close($mysqli);
?>
