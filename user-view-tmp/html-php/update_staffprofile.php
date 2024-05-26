<?php

global $dbc;

include '../../authorization.php';
checkUserRole('staff');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../project-2024-group-4/php/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob = htmlspecialchars($_POST['dob']);

    $sql = "UPDATE STAFF SET dob = ? WHERE staffID = ?";
    $stmt = $dbc->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $dob, $user_id);
        if ($stmt->execute()) {
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

