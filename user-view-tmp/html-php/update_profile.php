<?php

// update_profile.php
global $conn;
session_start();
include 'proba.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $location = htmlspecialchars($_POST["location"]);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Update user data
        $sql = "UPDATE customer SET email = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $location, $user_id);
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
        } else {
            $msg = "Error updating profile.";
        }
    } else {
        $msg = "Invalid email format.";
    }
}

// Redirect back to edit profile page with message
$_SESSION['msg'] = $msg;
header("Location: edit_profile.php");
exit();

?>