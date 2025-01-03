<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $interests = isset($_POST['interests']) ? $_POST['interests'] : [];
    $availability = isset($_POST['availability']) ? $_POST['availability'] : [];

    // Convert arrays to comma-separated strings for storage or processing
    $interests_str = implode(', ', $interests);
    $availability_str = implode(', ', $availability);

    // Check if clientID is set in the session
    if (!isset($_SESSION['clientID'])) {
        // Redirect with error message if clientID is not set
        $message = "Client not logged in.";
        header("Location: index.html?message=" . urlencode($message));
        exit();
    }

    // Get the clientID from the session
    $clientID = $_SESSION['clientID'];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barakahfoodbank";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO volunteer (VolunteerInterest, VolunteerAvailability, ClientID) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $interests_str, $availability_str, $clientID);
        
        if ($stmt->execute()) {
            // Redirect to successVolunteerRegistration.html
            header("Location: successVolunteerRegistration.html");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }

    $conn->close();
    
} else {
    // Redirect to index.html if not a POST request
    header("Location: index.html");
    exit();
}
?>
