<?php
// change_password.php
global $dbc;
session_start();
include 'proba.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Fetch the current password from the database
    $sql = "SELECT password FROM customer WHERE id = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (password_verify($current_password, $row['password'])) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE customer SET password = ? WHERE id = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $msg = "Password changed successfully!";
            } else {
                $msg = "Error updating password.";
            }
        } else {
            $msg = "New password and confirm password do not match.";
        }
    } else {
        $msg = "Current password is incorrect.";
    }
}

// Redirect back to edit profile page with message
$_SESSION['msg'] = $msg;
header("Location: edit_profile.php");
exit();
?>
