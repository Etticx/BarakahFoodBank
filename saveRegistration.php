<?php

require_once 'dbConnect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientUsername = test_input($_POST['clientUsername']);
    $clientPassword = test_input($_POST['clientPassword']);
    $clientFullName = test_input($_POST['clientFullName']);
    $clientEmailAddress = test_input($_POST['clientEmailAddress']);
    $clientPhoneNumber = test_input($_POST['clientPhoneNumber']);
    $clientFullAddress = test_input($_POST['clientFullAddress']);
    $clientCity = test_input($_POST['clientCity']);
    $clientPoscode = test_input($_POST['clientPoscode']);
    $clientState = test_input($_POST['clientState']);

    // Check if the email address already exists
    $checkEmailQuery = "SELECT clientEmailAddress FROM client WHERE clientEmailAddress = '$clientEmailAddress'";
    $emailResult = mysqli_query($mysqli, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        echo "<script type='text/javascript'> alert('Account with this email address is already exists. Please use a different email address.'); window.history.back();</script>";
    } else {
        // Insert the new record if email doesn't exist
        $sql = "INSERT INTO client (clientUsername, clientPassword, clientFullName, clientEmailAddress, clientFullAddress, clientCity, clientPoscode, clientState, clientPhoneNumber) VALUES ('$clientUsername', '$clientPassword', '$clientFullName', '$clientEmailAddress', '$clientFullAddress', '$clientCity', '$clientPoscode', '$clientState', '$clientPhoneNumber')";
        $result = mysqli_query($mysqli, $sql);

        if ($result) {
            echo "<script type='text/javascript'> alert('Records inserted successfully.'); window.location.href='loginform.html';</script>";
        } else {
            if (mysqli_errno($mysqli) == 1062) {
                echo "<script type='text/javascript'> alert('ERROR: Duplicate entry. Please update the information!'); </script>";
            } else {
                echo "<script type='text/javascript'> alert('ERROR: Could not execute the query.'); </script>";
            }
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
