<?php
global $dbc;
session_start();
include 'proba.php'; // Adjust this path as needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../project-2024-group-4/php/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password from the database
    $sql = "SELECT password FROM CUSTOMER WHERE customerID = ?";
    $stmt = $dbc->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();

        // Check if the current password matches
        if ($current_password === $db_password) { // Assuming passwords are stored in plain text (not recommended). For hashed passwords, use password_verify() function.
            // Check if the new password and confirm password match
            if ($new_password === $confirm_password) {
                // Update the password in the database
                $sql = "UPDATE CUSTOMER SET password = ? WHERE customerID = ?";
                $stmt = $dbc->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("si", $new_password, $user_id); // Use password_hash() if passwords are hashed
                    if ($stmt->execute()) {
                        // Password updated successfully
                        header("Location: profile.php?message=Password updated successfully");
                        exit();
                    } else {
                        echo "Error updating password: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $dbc->error;
                }
            } else {
                echo "New password and confirm password do not match.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "Error preparing statement: " . $dbc->error;
    }
} else {
    echo "Invalid request method.";
}
?>

