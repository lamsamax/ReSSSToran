<?php
global $dbc;

include '../../authorization.php';
checkUserRole('customer');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../project-2024-group-4/php/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT password FROM CUSTOMER WHERE customerID = ?";
    $stmt = $dbc->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();

        if ($current_password === $db_password) {
            if ($new_password === $confirm_password) {
                $sql = "UPDATE CUSTOMER SET password = ? WHERE customerID = ?";
                $stmt = $dbc->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("si", $new_password, $user_id);
                    if ($stmt->execute()) {
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


