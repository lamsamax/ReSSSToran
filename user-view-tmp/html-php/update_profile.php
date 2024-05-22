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
    // Sanitize and validate the input
    $dob = htmlspecialchars($_POST['dob']);

    // Prepare the SQL update statement
    $sql = "UPDATE CUSTOMER SET dob = ? WHERE customerID = ?";
    $stmt = $dbc->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $dob, $user_id);
        if ($stmt->execute()) {
            // Update successful, redirect back to profile page
            header("Location: profile.php");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $dbc->error;
    }
} else {
    echo "Invalid request method.";
}

